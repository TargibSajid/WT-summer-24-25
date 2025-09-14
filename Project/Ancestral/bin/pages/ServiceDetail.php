<?php
session_start();
require 'Config.php';


// Check if 'service_id' exists in the URL
if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];

    // Sanitize it if needed (convert to integer)
    $service_id = (int)$service_id;

    
} 




$successMessage = "";
$errors = [];

$stmt = $con->prepare("select * from services where service_id = ? ");
$stmt->bind_param('i',$service_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();




if ($_SERVER["REQUEST_METHOD"] === "POST") {


 if (!isset($_SESSION['user_id'])) {
    $errors[] = "You must be logged in to submit a request.";
} else {
    $userid = $_SESSION['user_id'];
}  $full_name      = $_POST['fullName'] ;
    $email          = $_POST['email'] ;
    $phone_number   = $_POST['phone'] ;
    $national_id_number = $_POST['nid'] ;
    $location       = $_POST['location'];
    $project_details= $_POST['details'];
    $requirement_details = $_POST['timeline'] ; 
    $estimated_budget= $_POST['budget'] ;
    $expected_timeline= $_POST['timeline'] ;



    $nidFileData = null;
    if (isset($_FILES['nid_scan']) && $_FILES['nid_scan']['error'] === 0) {
        $nidFileData = file_get_contents($_FILES['nid_scan']['tmp_name']);
    }


    $paymentFileData = null;
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === 0) {
        $paymentFileData = file_get_contents($_FILES['payment_proof']['tmp_name']);
    }

   
$stmt = $con->prepare("
    INSERT INTO service_requests
    (service_id,user_id,full_name, email, phone_number, national_id_number, nid_passport_file, payment_proof_file,
    location, project_details, requirement_details, estimated_budget, expected_timeline)
    VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");


$stmt->bind_param(
    "iissssssssssd", 
     $service_id,$userid,$full_name, $email, $phone_number, $national_id_number,
    $nidFileData, $paymentFileData, $location, $project_details, $requirement_details,
    $estimated_budget, $expected_timeline
);


if ($nidFileData !== null) {
    $stmt->send_long_data(4, $nidFileData); 
}
if ($paymentFileData !== null) {
    $stmt->send_long_data(5, $paymentFileData);
}

if ($stmt->execute()) {
    $successMessage = "Request submitted successfully!";
    $_SESSION['last_submission'] = [
        'id' => $stmt->insert_id,
        'full_name' => $full_name
    ];
} else {
    $errors[] = "Database error: " . $stmt->error;
}


    $stmt->close();
}

$con->close();
?>




<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Get Started — AI Solutions | Ancestral</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../cascade/HomePage.css">
  <link rel="stylesheet" href="../cascade/Service.css">
  <link rel="stylesheet"  href="../cascade/ServiceDetail.css ? <?php echo time();?>">
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

    <div class = "Name-container">
    <h2 id="sname" style="margin:0 0 14px 0;"><?php  echo $row['service_name'] ?> — Request & Booking</h2>
    </div>

    <div class = "main-container">
    <form id="serviceForm" method="POST" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="action" value="submit_request">
      <input type="hidden" name="service" value="AI">
      <div style="display:flex; gap:10px; align-items:center; margin-bottom:10px;">
        <img id = "srlogo" src="#" alt="" style="width:48px;height:48px;object-fit:contain;">
        <div>
          <h3 style="margin:0;"><?php  echo $row['service_name'] ?></h3>
          <div class="small-note">Fill the form to request the service. We require ID & contact verification to avoid fraud.</div>
        </div>
      </div>

      <div class="field">
        <label for="fullName">Full Name</label>
        <input id="fullName" name="fullName" type="text" required minlength="3">
      </div>

      <div class="field">
        <label for="email">Email Address</label>
        <input id="email" name="email" type="email" required>
      </div>

      <div class="field">
        <label for="phone">Phone Number (with country code)</label>
        <input id="phone" name="phone" type="tel" required placeholder="+8801...">
      </div>

      <div class="field">
        <label for="nid">National ID Number</label>
        <input id="nid" name="nid" type="text" required pattern="[0-9]{8,20}" placeholder="digits only">
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
        <input id="location" name="location" type="text" required>
      </div>

      <div class="field">
        <label for="budget">Estimated Budget (numbers only)</label>
        <input id="budget" name="budget" type="number" required min="1000">
        <div class="small-note">We use this to propose packages. Minimum 1000.</div>
      </div>

      <div class="field">
        <label for="timeline">Expected Timeline</label>
        <input id="timeline" name="timeline" type="text" placeholder="e.g. 3 months">
      </div>

      <div class="field">
        <label for="details">Project Details</label>
        <textarea id="details" name="details" rows="5" required></textarea>
      </div>

      <div class="meeting-options">
        <h4 style="margin:0 0 8px 0;">Meeting arrangements</h4>
        <div class="field">
          <label for="meetingType">Where are you located?</label>
          <select id="meetingType" name="meetingType" onchange="toggleMeetingOptions()" required>
            <option value="">-- Select --</option>
            <option value="Bangladesh">Inside Bangladesh (Face-to-face)</option>
            <option value="Outside">Outside Bangladesh (Online)</option>
          </select>
        </div>

        <div id="faceToFace" style="display:none;">
          <div class="field">
            <label for="appointmentDate">Preferred appointment date (office)</label>
            <input id="appointmentDate" name="appointmentDate" type="date">
          </div>
          <div class="small-note">We'll check provider availability and confirm a slot.</div>
        </div>

        <div id="onlineMeeting" style="display:none;">
          <div class="field">
            <label for="platform">Online platform</label>
            <select id="platform" name="platform">
              <option value="">-- Choose Platform --</option>
              <option value="Zoom">Zoom</option>
              <option value="Skype">Skype</option>
              <option value="WhatsApp">WhatsApp</option>
              <option value="Discord">Discord</option>
            </select>
          </div>
          <div class="field">
            <label for="meetingDate">Preferred meeting date/time</label>
            <input id="meetingDate" name="meetingDate" type="date">
          </div>
          <div class="small-note">We will send an invite link once the meeting is confirmed.</div>
        </div>
      </div>

      <div class="field" style="margin-top:12px;">
        <input id="agree" name="agree" type="checkbox" required>
        <label for="agree" style="display:inline;font-weight:600;">I confirm this information is true and accept verification & contact</label>
      </div>

      <div style="margin-top:12px;">
        <button type="submit" class="get-started-btn">Submit Request</button>
        <a class="back-link" href="Service.php">← Back to Services</a>
      </div>
    </form>
</div>

    <!-- RIGHT: Service details -->
    <div class="right">
      <h3 style="margin-top:0;"><?php  echo $row['service_name'] ?> — Overview</h3>
      <p class="small-note">
      <?php  echo $row['description'] ?>  
      Example stack, cost tiers and deliverables. Edit as required for each service.</p>

      <div style="margin-top:10px;">
        <div style="display:flex;justify-content:space-between;"><span>Starter</span><strong>৳ <?php  echo $row['pricing_starter'] ?></strong></div>
        <div class="small-note">Prototype, 1–2 week turnaround.</div>

        <div style="display:flex;justify-content:space-between;margin-top:8px;"><span>Business</span><strong>৳ <?php  echo $row['pricing_business'] ?></strong></div>
        <div class="small-note">MVP with APIs and DB, 4–8 weeks.</div>

        <div style="display:flex;justify-content:space-between;margin-top:8px;"><span>Enterprise</span><strong>৳ <?php  echo $row['pricing_enterprise'] ?>+</strong></div>
        <div class="small-note">Full product, deployment, maintenance.</div>
      </div>

      <hr>

      <h4>Typical tech stack</h4>

      <?php  $techArray = json_decode($row['technologies_used'], true);?>

      <ul>
        <li><strong>Languages:</strong> <?php echo $techArray[0]  ?></li>
        <li><strong>AI:</strong> <?php echo $techArray[2] ."/". $techArray[1]   ?></li>

      </ul>

      <hr>
<?php  $devArray = json_decode($row['deliverables'], true);?>
      <h4>Deliverables</h4>
      <ul>
        <li> <?php echo $devArray[0]  ?></li>
        <li><?php echo $devArray[1]  ?></li>
        <li><?php echo $devArray[2]  ?></li>
        <li><?php echo $devArray[3]  ?></li>
      </ul>

    </div>

      
      <div>
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
  
        <?php  $adonArray = json_decode($row['optional_addons'], true);?>


        <ul>
          <li><input type="checkbox" id="addon1"><?php echo $adonArray[0]  ?> </li>
          <li><input type="checkbox" id="addon2"> <?php echo $adonArray[1]  ?> </li>
          <li><input type="checkbox" id="addon3"> <?php echo $adonArray[2]  ?> </li>
          <li><input type="checkbox" id="addon4"> <?php echo $adonArray[3]  ?> </li>
        </ul>
      </div>


    </div>
</main>


  <footer class="footer" id="footer" style="top: 40px; position: relative;">
    <div class="footer-bottom">
      <p>Copyright © 2025 All Rights Reserved</p>
    </div>
  </footer>


  <script src = "../interactivity/ServiceDetail.js? <?php  echo time();  ?>"> </script>

  </body>
  </html>  
