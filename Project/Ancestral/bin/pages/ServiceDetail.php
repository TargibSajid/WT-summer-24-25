<?php
// ServiceDetails.php
// Single-file: shows service details form, handles submission, file uploads, simple KYC flow with OTP verification.
// Requirements: PHP 7+, allow_file_uploads = On, write permission to this script directory for uploads and submissions.json
session_start();

/* -------------------- Configuration -------------------- */
$UPLOAD_DIR = __DIR__ . '/uploads';         // where uploaded files go
$SUBMISSIONS_FILE = __DIR__ . '/submissions.json'; // simple JSON store (use DB in production)
$MAX_FILE_SIZE = 5 * 1024 * 1024;           // 5 MB
$ALLOWED_MIMES = ['image/jpeg','image/png','application/pdf','image/jpg']; // allowed file types
$MIN_BUDGET = 1000; // minimum budget in chosen currency (adjust if needed)

/* -------------------- Helpers -------------------- */
function safe($v) { return htmlspecialchars($v ?? '', ENT_QUOTES); }
function ensure_upload_dir($dir) {
    if (!file_exists($dir)) mkdir($dir, 0755, true);
}
function save_uploaded_file($file, $destDir, $maxSize, $allowedMimes, &$err = null) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        $err = 'No file uploaded.';
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $err = 'File upload error code: ' . $file['error'];
        return null;
    }
    if ($file['size'] > $maxSize) {
        $err = 'File too large. Max ' . ($maxSize/1024/1024) . 'MB.';
        return null;
    }
    // check mime type using finfo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, $allowedMimes)) {
        $err = 'Invalid file type: ' . $mime;
        return null;
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    // generate unique filename
    $unique = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
    $dest = rtrim($destDir, '/') . '/' . $unique;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        $err = 'Failed to move uploaded file.';
        return null;
    }
    return $unique;
}
function load_submissions($path) {
    if (!file_exists($path)) return [];
    $json = file_get_contents($path);
    $arr = json_decode($json, true);
    return is_array($arr) ? $arr : [];
}
function append_submission($path, $entry) {
    $arr = load_submissions($path);
    $arr[] = $entry;
    // atomic write
    file_put_contents($path, json_encode($arr, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), LOCK_EX);
}

/* -------------------- Service map -------------------- */
$map = [
  'AI'    => ['title' => 'AI Solutions', 'icon' => '../../AI.png'],
  'Web'   => ['title' => 'Web & Mobile App Development', 'icon' => '../../WEB.png'],
  'Cloud' => ['title' => 'Cloud Solutions & Hosting', 'icon' => '../../CLOUD.png'],
  'UI'    => ['title' => 'UI/UX Design', 'icon' => '../../UI.png'],
  'Data'  => ['title' => 'Data Science & Analytics', 'icon' => '../../DATA.png'],
  // extend as needed
];
$serviceKeyRaw = $_GET['service'] ?? 'AI';
$serviceKey = array_key_exists($serviceKeyRaw, $map) ? $serviceKeyRaw : 'AI';
$serviceTitle = $map[$serviceKey]['title'];
$serviceIcon = $map[$serviceKey]['icon'];

/* -------------------- POST handling -------------------- */
$errors = [];
$successMessage = '';
$showOtpForm = false;
$submissionId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'submit_request';

    if ($action === 'submit_request') {
        // sanitize inputs
        $fullName = trim($_POST['fullName'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $nid = trim($_POST['nid'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $budget = trim($_POST['budget'] ?? '');
        $timeline = trim($_POST['timeline'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $meetingType = $_POST['meetingType'] ?? '';
        $appointmentDate = $_POST['appointmentDate'] ?? '';
        $platform = $_POST['platform'] ?? '';
        $meetingDate = $_POST['meetingDate'] ?? '';
        $agree = isset($_POST['agree']) ? true : false;

        // Server-side validation
        if (strlen($fullName) < 3) $errors[] = "Full name must be at least 3 characters.";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email is invalid.";
        if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) $errors[] = "Phone must include country code and be 10–15 digits.";
        if (!preg_match('/^[0-9]{8,20}$/', $nid)) $errors[] = "NID must be digits only (8–20).";
        if (strlen($location) < 3) $errors[] = "Location must be provided.";
        if (!is_numeric($budget) || (float)$budget < $MIN_BUDGET) $errors[] = "Budget must be a number and at least $MIN_BUDGET.";
        if (strlen($details) < 10) $errors[] = "Please describe the project in more detail (min 10 chars).";
        if (!$agree) $errors[] = "You must confirm the information and agree to verification.";

        // meeting validations
        if ($meetingType !== 'Bangladesh' && $meetingType !== 'Outside') $errors[] = "Select a valid meeting location (Inside/Outside Bangladesh).";
        if ($meetingType === 'Bangladesh') {
            // appointmentDate optional but preferred — if provided, ensure valid date string
            if ($appointmentDate && !strtotime($appointmentDate)) $errors[] = "Invalid appointment date.";
        } else { // Outside
            if (!$platform) $errors[] = "Please choose an online meeting platform.";
            if ($meetingDate && !strtotime($meetingDate)) $errors[] = "Invalid meeting date.";
        }

        // handle file uploads
        ensure_upload_dir($UPLOAD_DIR);
        $nid_scan_name = null;
        $payment_proof_name = null;

        // NID scan REQUIRED
        if (!isset($_FILES['nid_scan'])) {
            $errors[] = "Please upload scanned NID or passport.";
        } else {
            $err = null;
            $res = save_uploaded_file($_FILES['nid_scan'], $UPLOAD_DIR, $MAX_FILE_SIZE, $ALLOWED_MIMES, $err);
            if ($res === null) $errors[] = "NID upload error: $err";
            else $nid_scan_name = $res;
        }
        // payment proof optional
        if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] !== UPLOAD_ERR_NO_FILE) {
            $err = null;
            $res = save_uploaded_file($_FILES['payment_proof'], $UPLOAD_DIR, $MAX_FILE_SIZE, $ALLOWED_MIMES, $err);
            if ($res === null) $errors[] = "Payment proof upload error: $err";
            else $payment_proof_name = $res;
        }

        if (empty($errors)) {
            // create submission entry and persist to JSON (in production use DB)
            $submissionId = 'SUB' . time() . '_' . bin2hex(random_bytes(4));
            $entry = [
                'id' => $submissionId,
                'service_key' => $serviceKey,
                'service_title' => $serviceTitle,
                'fullName' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'nid' => $nid,
                'nid_scan_file' => $nid_scan_name,
                'payment_proof_file' => $payment_proof_name,
                'location' => $location,
                'budget' => (float)$budget,
                'timeline' => $timeline,
                'details' => $details,
                'meetingType' => $meetingType,
                'appointmentDate' => $appointmentDate,
                'platform' => $platform,
                'meetingDate' => $meetingDate,
                'status' => 'pending_verification',
                'created_at' => date('c'),
            ];
            append_submission($SUBMISSIONS_FILE, $entry);

            // generate OTP and store in session for demo KYC verification
            $otp = random_int(100000, 999999);
            // Store OTP and submission id in session (production: don't store sensitive info unencrypted)
            $_SESSION['otp_for_submission'] = $otp;
            $_SESSION['otp_submission_id'] = $submissionId;
            $_SESSION['last_submission'] = $entry;

            // In production: send OTP via SMS or email. Here we will show OTP only for demo.
            $showOtpForm = true;
            $successMessage = "Request saved. OTP has been generated and (in production) sent to the user's phone/email.";
        }
    } elseif ($action === 'verify_otp') {
        // verify OTP
        $providedOtp = trim($_POST['otp'] ?? '');
        $expected = $_SESSION['otp_for_submission'] ?? null;
        $subId = $_SESSION['otp_submission_id'] ?? null;
        if (!$expected || !$subId) {
            $errors[] = "No OTP request found. Please submit the form first.";
        } else if ($providedOtp !== (string)$expected) {
            $errors[] = "Incorrect OTP. Please try again.";
            $showOtpForm = true;
        } else {
            // mark submission as verified in JSON store
            $arr = load_submissions($SUBMISSIONS_FILE);
            foreach ($arr as &$s) {
                if ($s['id'] === $subId) {
                    $s['status'] = 'verified';
                    $s['verified_at'] = date('c');
                    break;
                }
            }
            // write back
            file_put_contents($SUBMISSIONS_FILE, json_encode($arr, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), LOCK_EX);
            // clear OTP from session
            unset($_SESSION['otp_for_submission'], $_SESSION['otp_submission_id']);
            $successMessage = "Verification successful. Submission $subId is now verified. We'll contact you to schedule the meeting.";
        }
    }
}

















/* -------------------- HTML Output (Form / Messages) -------------------- */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Get Started — <?php echo safe($serviceTitle); ?> | Ancestral</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../cascade/HomePage.css?<?php echo time(); ?>">
  <link rel="stylesheet" href="../cascade/Service.css?<?php echo time(); ?>">
  <link rel="stylesheet"  href="../cascade/ServiceDetail.css?<?php echo time(); ?>">
</head>
<body>

  <!-- Header -->
  <div class="header-container">
    <header id="main-header">
      <h1>ANCESTRAL</h1>
      <img src="../../Logo.svg" alt="Ancestral Logo" class="Logo">
      <nav class="nav">
        <a href="Service.php">Services</a>
        <a href="#">Blog/News</a>
        <a href="About.php">About</a>
        <a href="#" id="Contact">Contact</a>
        <a href="#" class="btn">Get Started</a>
      </nav>
    </header>
  </div>

  <main class="wrapper">
    <h2 style="margin:0 0 14px 0;"><?php echo safe($serviceTitle); ?> — Request & Booking</h2>

    <?php if (!empty($errors)): ?>
      <div class="errors">
        <strong>Please fix these issues:</strong>
        <ul>
          <?php foreach ($errors as $err): ?><li><?php echo safe($err); ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($successMessage): ?>
      <div class="success"><?php echo safe($successMessage); ?></div>
    <?php endif; ?>

    <div class="service-page">

      <!-- LEFT: Form / OTP -->
      <div class="left">
        <?php if ($showOtpForm && isset($_SESSION['last_submission'])): ?>
          <!-- OTP verification step -->
          <h3>Verify your request (OTP)</h3>
          <p class="small-note">We generated a verification code and (in production) sent it to the phone/email you provided.</p>

          <form method="POST" novalidate>
            <input type="hidden" name="action" value="verify_otp">
            <div class="field">
              <label for="otp">Enter OTP</label>
              <input id="otp" name="otp" type="text" maxlength="6" required placeholder="6-digit code">
            </div>
            <button class="get-started-btn" type="submit">Verify OTP</button>
            <div style="margin-top:10px;" class="small-note">
              Submission ID: <strong><?php echo safe($_SESSION['last_submission']['id']); ?></strong>
            </div>
            <div class="small-note" style="margin-top:8px; color:#b71c1c;">
              <!-- demo-only OTP reveal: remove in production -->
              For demo only — OTP: <strong><?php echo safe($_SESSION['otp_for_submission'] ?? ''); ?></strong>
            </div>
          </form>

        <?php else: ?>
          <!-- Request form -->
          <form id="serviceForm" method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="action" value="submit_request">
            <input type="hidden" name="service" value="<?php echo safe($serviceKey); ?>">
            <div style="display:flex; gap:10px; align-items:center; margin-bottom:10px;">
              <img src="<?php echo safe($serviceIcon); ?>" alt="" style="width:48px;height:48px;object-fit:contain;">
              <div>
                <h3 style="margin:0;"><?php echo safe($serviceTitle); ?></h3>
                <div class="small-note">Fill the form to request the service. We require ID & contact verification to avoid fraud.</div>
              </div>
            </div>

            <div class="field">
              <label for="fullName">Full Name</label>
              <input id="fullName" name="fullName" type="text" required minlength="3" value="<?php echo safe($_POST['fullName'] ?? ''); ?>">
            </div>

            <div class="field">
              <label for="email">Email Address</label>
              <input id="email" name="email" type="email" required value="<?php echo safe($_POST['email'] ?? ''); ?>">
            </div>

            <div class="field">
              <label for="phone">Phone Number (with country code)</label>
              <input id="phone" name="phone" type="tel" required placeholder="+8801..." value="<?php echo safe($_POST['phone'] ?? ''); ?>">
            </div>

            <div class="field">
              <label for="nid">National ID Number</label>
              <input id="nid" name="nid" type="text" required pattern="[0-9]{8,20}" placeholder="digits only" value="<?php echo safe($_POST['nid'] ?? ''); ?>">
            </div>

            <div class="field">
              <label for="nid_scan">Upload Scanned NID / Passport (front & back)</label>
              <input id="nid_scan" name="nid_scan" type="file" accept="image/*,application/pdf" required>
              <div class="small-note">Max 5MB. JPG/PNG/PDF.</div>
            </div>

            <div class="field">
              <label for="payment_proof">Upload Payment Proof (optional)</label>
              <input id="payment_proof" name="payment_proof" type="file" accept="image/*,application/pdf">
            </div>

            <div class="field">
              <label for="location">Location (City, Country)</label>
              <input id="location" name="location" type="text" required value="<?php echo safe($_POST['location'] ?? ''); ?>">
            </div>

            <div class="field">
              <label for="budget">Estimated Budget (numbers only)</label>
              <input id="budget" name="budget" type="number" required min="<?php echo $MIN_BUDGET; ?>" value="<?php echo safe($_POST['budget'] ?? ''); ?>">
              <div class="small-note">We use this to propose packages. Minimum <?php echo $MIN_BUDGET; ?>.</div>
            </div>

            <div class="field">
              <label for="timeline">Expected Timeline</label>
              <input id="timeline" name="timeline" type="text" placeholder="e.g. 3 months" value="<?php echo safe($_POST['timeline'] ?? ''); ?>">
            </div>

            <div class="field">
              <label for="details">Project Details</label>
              <textarea id="details" name="details" rows="5" required><?php echo safe($_POST['details'] ?? ''); ?></textarea>
            </div>

            <div class="meeting-options">
              <h4 style="margin:0 0 8px 0;">Meeting arrangements</h4>
              <div class="field">
                <label for="meetingType">Where are you located?</label>
                <select id="meetingType" name="meetingType" onchange="toggleMeetingOptions()" required>
                  <option value="">-- Select --</option>
                  <option value="Bangladesh" <?php if (($_POST['meetingType'] ?? '')==='Bangladesh') echo 'selected'; ?>>Inside Bangladesh (Face-to-face)</option>
                  <option value="Outside" <?php if (($_POST['meetingType'] ?? '')==='Outside') echo 'selected'; ?>>Outside Bangladesh (Online)</option>
                </select>
              </div>

              <div id="faceToFace" style="display:none;">
                <div class="field">
                  <label for="appointmentDate">Preferred appointment date (office)</label>
                  <input id="appointmentDate" name="appointmentDate" type="date" value="<?php echo safe($_POST['appointmentDate'] ?? ''); ?>">
                </div>
                <div class="small-note">We'll check provider availability and confirm a slot.</div>
              </div>

              <div id="onlineMeeting" style="display:none;">
                <div class="field">
                  <label for="platform">Online platform</label>
                  <select id="platform" name="platform">
                    <option value="">-- Choose Platform --</option>
                    <option value="Zoom" <?php if (($_POST['platform'] ?? '')==='Zoom') echo 'selected'; ?>>Zoom</option>
                    <option value="Skype" <?php if (($_POST['platform'] ?? '')==='Skype') echo 'selected'; ?>>Skype</option>
                    <option value="WhatsApp" <?php if (($_POST['platform'] ?? '')==='WhatsApp') echo 'selected'; ?>>WhatsApp</option>
                    <option value="Discord" <?php if (($_POST['platform'] ?? '')==='Discord') echo 'selected'; ?>>Discord</option>
                  </select>
                </div>
                <div class="field">
                  <label for="meetingDate">Preferred meeting date/time</label>
                  <input id="meetingDate" name="meetingDate" type="date" value="<?php echo safe($_POST['meetingDate'] ?? ''); ?>">
                </div>
                <div class="small-note">We will send an invite link once the meeting is confirmed.</div>
              </div>
            </div>

            <div class="field" style="margin-top:12px;">
              <input id="agree" name="agree" type="checkbox" <?php if(isset($_POST['agree'])) echo 'checked'; ?> required>
              <label for="agree" style="display:inline;font-weight:600;">I confirm this information is true and accept verification & contact</label>
            </div>

            <div style="margin-top:12px;">
              <button type="submit" class="get-started-btn">Submit Request</button>
              <a class="back-link" href="Service.php">← Back to Services</a>
            </div>
          </form>
        <?php endif; // end show OTP or form ?>
      </div>

      <!-- RIGHT: Service details -->
      <div class="right">
        <h3 style="margin-top:0;"><?php echo safe($serviceTitle); ?> — Overview</h3>
        <p class="small-note">Example stack, cost tiers and deliverables. Edit as required for each service.</p>

        <div style="margin-top:10px;">
          <div style="display:flex;justify-content:space-between;"><span>Starter</span><strong>৳ 20,000</strong></div>
          <div class="small-note">Prototype, 1–2 week turnaround.</div>

          <div style="display:flex;justify-content:space-between;margin-top:8px;"><span>Business</span><strong>৳ 80,000</strong></div>
          <div class="small-note">MVP with APIs and DB, 4–8 weeks.</div>

          <div style="display:flex;justify-content:space-between;margin-top:8px;"><span>Enterprise</span><strong>৳ 250,000+</strong></div>
          <div class="small-note">Full product, deployment, maintenance.</div>
        </div>

        <hr>

        <h4>Typical tech stack</h4>
        <ul>
          <li><strong>Languages:</strong> Python (AI), JavaScript/TypeScript</li>
          <li><strong>AI:</strong> PyTorch / TensorFlow</li>
          <li><strong>Frontend:</strong> React / Next.js</li>
          <li><strong>Infra:</strong> Docker, AWS/GCP</li>
        </ul>

        <hr>

        <h4>Deliverables</h4>
        <ul>
          <li>Proposal & contract</li>
          <li>Prototype / demo</li>
          <li>Deployed solution & docs</li>
          <li>Handover & training</li>
        </ul>

        </div>

        





            <div class="additional-section">

        <!-- Cost Estimator -->
        <div class="cost-estimator">
            <h2>Estimate Your Project Cost</h2>
            <label for="project-size">Project Size:</label>
            <select id="project-size">
                <option value="small">Small</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option>
            </select>

            <label for="feature-count">Extra Features:</label>
            <input type="number" id="feature-count" min="0" max="10" value="0">

            <button id="calculate-cost">Calculate Estimate</button>
            <p id="estimated-cost">Estimated Cost: $0</p>
        </div>



                <div class="optional-addons">
            <h2>Optional Add-ons</h2>
            <ul>
                <li><input type="checkbox" id="addon1"> Advanced AI Module</li>
                <li><input type="checkbox" id="addon2"> Extra Security Features</li>
                <li><input type="checkbox" id="addon3"> Maintenance Package</li>
                <li><input type="checkbox" id="addon4"> Data Analytics Dashboard</li>
            </ul>
        </div>

        <!-- Legal / Terms -->
        <div class="legal-terms">
            <h2>Terms & Conditions</h2>
            <p>By submitting this form, you agree to our service terms, privacy policy, and payment policies. Ensure all information is correct before proceeding. All personal data will be verified and kept confidential. Misrepresentation may result in request denial.</p>
        </div>


    </div>
  </main>

  <footer class="footer" id="footer" style="top: 40px; position: relative;">
    <div class="footer-bottom">
      <p>Copyright © 2025 All Rights Reserved</p>
    </div>
  </footer>

<script>
  // Toggle meeting option blocks
  function toggleMeetingOptions() {
    const mt = document.getElementById('meetingType').value;
    document.getElementById('faceToFace').style.display = mt === 'Bangladesh' ? 'block' : 'none';
    document.getElementById('onlineMeeting').style.display = mt === 'Outside' ? 'block' : 'none';
  }
  // initialize on page load (if form was posted or values prefilled)
  (function(){
    toggleMeetingOptions();
    // basic client-side file size check (helps user)
    const nidInput = document.getElementById('nid_scan');
    if (nidInput) {
      nidInput.addEventListener('change', function(){
        const file = this.files[0];
        if (!file) return;
        const maxMB = <?php echo $MAX_FILE_SIZE / (1024*1024); ?>;
        if (file.size > <?php echo $MAX_FILE_SIZE; ?>) {
          alert('NID file too large. Max ' + maxMB + 'MB.');
          this.value = '';
        }
      });
    }
  })();
</script>

</body>
</html>
