<?php
// admin_dashboard.php
// Single-file admin dashboard for Ancestral — works with submissions.json, users.json, views.json
// IMPORTANT: This is a starter admin. Add real authentication, CSRF protection, and database in production.

session_start();

// --- Config ---
$BASE_DIR = __DIR__;
$SUBMISSIONS_FILE = $BASE_DIR . '/submissions.json';
$USERS_FILE = $BASE_DIR . '/users.json';
$VIEWS_FILE = $BASE_DIR . '/views.json';
$UPLOADS_DIR = $BASE_DIR . '/uploads';
@mkdir($UPLOADS_DIR, 0755, true);

// Utility helpers
function load_json($path) {
    if (!file_exists($path)) {
        file_put_contents($path, json_encode([]));
        return [];
    }
    $s = file_get_contents($path);
    $a = json_decode($s, true);
    return is_array($a) ? $a : [];
}
function save_json($path, $arr) {
    file_put_contents($path, json_encode($arr, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), LOCK_EX);
}
function safe($v) { return htmlspecialchars($v ?? '', ENT_QUOTES); }

// load data
$submissions = load_json($SUBMISSIONS_FILE);
$users = load_json($USERS_FILE);
$views = load_json($VIEWS_FILE);

// ensure submissions have minimal fields (backfill)
foreach ($submissions as &$s) {
    if (!isset($s['id'])) $s['id'] = 'SUB'.(time()).'_'.bin2hex(random_bytes(3));
    if (!isset($s['created_at'])) $s['created_at'] = date('c');
    if (!isset($s['status'])) $s['status'] = 'pending_verification';
}
unset($s);

// --- Handle POST actions (status change, delete, add user, add post skeleton, export) ---
$messages = []; $errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'change_status') {
        $id = $_POST['id'] ?? '';
        $new = $_POST['status'] ?? '';
        foreach ($submissions as &$s) {
            if ($s['id'] === $id) {
                $s['status'] = $new;
                $s['status_changed_at'] = date('c');
                $messages[] = "Status of {$id} changed to {$new}.";
                break;
            }
        }
        unset($s);
        save_json($SUBMISSIONS_FILE, $submissions);
    } elseif ($action === 'delete_submission') {
        $id = $_POST['id'] ?? '';
        $found = false;
        foreach ($submissions as $k => $s) {
            if ($s['id'] === $id) {
                // optionally remove uploaded files (be careful)
                if (!empty($s['nid_scan_file']) && file_exists($UPLOADS_DIR . '/' . $s['nid_scan_file'])) {
                    @unlink($UPLOADS_DIR . '/' . $s['nid_scan_file']);
                }
                if (!empty($s['payment_proof_file']) && file_exists($UPLOADS_DIR . '/' . $s['payment_proof_file'])) {
                    @unlink($UPLOADS_DIR . '/' . $s['payment_proof_file']);
                }
                array_splice($submissions, $k, 1);
                $found = true;
                $messages[] = "Submission $id deleted.";
                break;
            }
        }
        if (!$found) $errors[] = "Submission $id not found.";
        save_json($SUBMISSIONS_FILE, $submissions);
    } elseif ($action === 'export_csv') {
        // generate CSV and force download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=submissions_export_' . date('Ymd_His') . '.csv');
        $out = fopen('php://output', 'w');
        // headers
        fputcsv($out, ['id','service_title','fullName','email','phone','nid','location','budget','timeline','meetingType','appointmentDate','platform','meetingDate','status','created_at']);
        foreach ($submissions as $s) {
            fputcsv($out, [
                $s['id'] ?? '',
                $s['service_title'] ?? '',
                $s['fullName'] ?? '',
                $s['email'] ?? '',
                $s['phone'] ?? '',
                $s['nid'] ?? '',
                $s['location'] ?? '',
                isset($s['budget']) ? (string)$s['budget'] : '',
                $s['timeline'] ?? '',
                $s['meetingType'] ?? '',
                $s['appointmentDate'] ?? '',
                $s['platform'] ?? '',
                $s['meetingDate'] ?? '',
                $s['status'] ?? '',
                $s['created_at'] ?? '',
            ]);
        }
        fclose($out);
        exit;
    } elseif ($action === 'add_user') {
        $uname = trim($_POST['username'] ?? '');
        $uemail = trim($_POST['useremail'] ?? '');
        if (!$uname || !filter_var($uemail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Provide valid username and email.";
        } else {
            $users[] = ['id'=>'USR'.time().'_'.bin2hex(random_bytes(3)), 'name'=>$uname, 'email'=>$uemail, 'created_at'=>date('c')];
            save_json($USERS_FILE,$users);
            $messages[] = "User $uname added.";
        }
    } elseif ($action === 'delete_user') {
        $uid = $_POST['userid'] ?? '';
        $found = false;
        foreach ($users as $k => $u) {
            if (($u['id'] ?? '') === $uid) {
                array_splice($users, $k, 1); $found = true; break;
            }
        }
        if ($found) { save_json($USERS_FILE,$users); $messages[]="User deleted."; }
        else $errors[] = "User not found.";
    } elseif ($action === 'clear_views') {
        $views = ['count'=>0];
        save_json($VIEWS_FILE,$views);
        $messages[] = "View count reset.";
    } elseif ($action === 'create_post') {
        // Simple skeleton: store in file posts.json (create file)
        $posts_file = $BASE_DIR . '/posts.json';
        $posts = load_json($posts_file);
        $title = trim($_POST['post_title'] ?? '');
        $content = trim($_POST['post_content'] ?? '');
        if (!$title || !$content) $errors[] = "Post title and content required.";
        else {
            $posts[] = ['id'=>'POST'.time().'_'.bin2hex(random_bytes(3)),'title'=>$title,'content'=>$content,'created_at'=>date('c')];
            save_json($posts_file, $posts);
            $messages[] = "Post created.";
        }
    }
}

// Save any write-backs already done above
// (actions that changed $submissions or $users will have saved them)

// --- Compute metrics ---
$total_subs = count($submissions);
$verified = count(array_filter($submissions, fn($s)=>isset($s['status']) && $s['status']==='verified'));
$pending = count(array_filter($submissions, fn($s)=>isset($s['status']) && $s['status']==='pending_verification'));
$approved = count(array_filter($submissions, fn($s)=>isset($s['status']) && $s['status']==='approved'));
$rejected = count(array_filter($submissions, fn($s)=>isset($s['status']) && $s['status']==='rejected'));
$total_users = count($users);
$total_views = ($views['count'] ?? 0);
$total_budget_sum = array_sum(array_map(fn($s)=>isset($s['budget']) ? (float)$s['budget'] : 0, $submissions));

// sort submissions newest first
usort($submissions, function($a,$b){
    return strtotime($b['created_at'] ?? 0) <=> strtotime($a['created_at'] ?? 0);
});

// --- Filtering (GET params) ---
$filter_service = $_GET['service'] ?? '';
$filter_status = $_GET['status'] ?? '';
$search_q = trim($_GET['q'] ?? '');

// filtered list for display
$display_submissions = array_filter($submissions, function($s) use ($filter_service,$filter_status,$search_q) {
    if ($filter_service && (($s['service_key'] ?? '') !== $filter_service) && stripos($s['service_title'] ?? '', $filter_service) === false) return false;
    if ($filter_status && ($s['status'] ?? '') !== $filter_status) return false;
    if ($search_q) {
        $hay = implode(' ', array_values($s));
        if (stripos($hay, $search_q) === false) return false;
    }
    return true;
});

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Ancestral Admin Dashboard</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/modern-normalize/1.1.0/modern-normalize.min.css">
<style>
/* --- Admin CSS (rich version) --- */
:root{
  --primary:#5b21b6;
  --muted:#6b7280;
  --bg:#f4f6f9;
}
*{box-sizing:border-box}
body{font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial; background:var(--bg); margin:0; color:#111}
.header{display:flex;align-items:center;justify-content:space-between;padding:14px 22px;background:#fff;border-bottom:1px solid #e6e9ef}
.header .brand{display:flex;align-items:center;gap:12px}
.header .brand img{width:36px;height:36px}
.header h1{font-size:18px;margin:0}
.container{display:flex;min-height:calc(100vh - 66px)}
.sidebar{width:260px;background:#111827;color:#fff;padding:20px;position:sticky;top:0;height:calc(100vh - 66px)}
.sidebar h2{font-size:18px;margin-bottom:8px}
.sidebar nav a{display:block;color:#e6e6e6;padding:10px;border-radius:8px;text-decoration:none;margin:6px 0}
.sidebar nav a:hover{background:#11182710;color:#fff}
.main{flex:1;padding:22px;overflow:auto}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:18px}
.card{background:#fff;padding:12px;border-radius:10px;box-shadow:0 6px 18px rgba(17,24,39,0.06)}
.card h3{margin:0;font-size:13px;color:var(--muted)}
.card p{font-size:20px;margin:6px 0 0;font-weight:700}
.layout{display:grid;grid-template-columns:1fr 420px;gap:18px}
.leftCol{background:transparent}
.panel{background:#fff;padding:14px;border-radius:10px;box-shadow:0 6px 18px rgba(17,24,39,0.04)}
.submissions-list table{width:100%;border-collapse:collapse;font-size:14px}
.submissions-list th, .submissions-list td{padding:10px;border-bottom:1px solid #f1f3f6;text-align:left}
.submissions-list tr:hover{background:#fbfbfd;cursor:pointer}
.status-pill{display:inline-block;padding:6px 10px;border-radius:999px;font-size:12px}
.status-pending{background:#fff7ed;color:#92400e;border:1px solid #ffedd5}
.status-verified{background:#ecfdf5;color:#065f46;border:1px solid #bbf7d0}
.status-approved{background:#eef2ff;color:#3730a3;border:1px solid #e0e7ff}
.status-rejected{background:#fff1f2;color:#7f1d1d;border:1px solid #fecaca}

.detail-panel{position:sticky;top:22px}
.detail-panel h3{margin-top:0}
.detail-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed #f1f3f6}
.detail-row small{color:var(--muted)}
.actions{display:flex;gap:8px;margin-top:12px}
.btn{padding:8px 10px;border-radius:8px;border:none;cursor:pointer;font-weight:600}
.btn-primary{background:var(--primary);color:#fff}
.btn-muted{background:#f1f5f9;color:#111}
.filters{display:flex;gap:10px;align-items:center;margin-bottom:12px}
.search{flex:1;display:flex;gap:8px}
input[type="text"], select{padding:8px;border:1px solid #e6e9ef;border-radius:8px}
.small-note{font-size:13px;color:var(--muted);margin-top:8px}
.footer-note{font-size:12px;color:var(--muted);margin-top:12px}
.icon{width:22px;height:22px;display:inline-block;vertical-align:middle}
.badge{background:#f3f4f6;padding:4px 8px;border-radius:999px;font-size:12px}
.posts-area .post{padding:10px;border-bottom:1px solid #f1f3f6}
.kv{color:var(--muted);font-size:13px}
.uplink{display:inline-block;margin-left:6px;color:var(--primary);text-decoration:underline}
@media(max-width:980px){.layout{grid-template-columns:1fr}}
</style>
</head>
<body>

<!-- Header -->
<div class="header">
  <div class="brand">
    <img src="../../Logo.svg" alt="logo">
    <h1>Ancestral Admin</h1>
  </div>
  <div>
    <form method="GET" style="display:flex;gap:8px;align-items:center">
      <input type="text" name="q" placeholder="Search submissions, names, email..." value="<?php echo safe($search_q); ?>" style="padding:8px;border-radius:8px;border:1px solid #e6e9ef">
      <select name="service" style="padding:8px;border-radius:8px;border:1px solid #e6e9ef">
        <option value="">All services</option>
        <?php
          $services = array_values(array_unique(array_map(fn($s)=>$s['service_key'] ?? ($s['service_title'] ?? ''), $submissions)));
          foreach ($services as $sv) {
            if (!$sv) continue;
            $sel = ($filter_service === $sv) ? 'selected' : '';
            echo "<option value=\"".safe($sv)."\" $sel>".safe($sv)."</option>";
          }
        ?>
      </select>
      <select name="status" style="padding:8px;border-radius:8px;border:1px solid #e6e9ef">
        <option value="">All statuses</option>
        <?php foreach (['pending_verification'=>'Pending','verified'=>'Verified','approved'=>'Approved','rejected'=>'Rejected'] as $k=>$v) {
            $sel = ($filter_status === $k) ? 'selected' : '';
            echo "<option value=\"".safe($k)."\" $sel>".safe($v)."</option>";
        } ?>
      </select>
      <button type="submit" class="btn btn-primary">Filter</button>
      &nbsp;
      <form method="POST" style="display:inline">
        <input type="hidden" name="action" value="export_csv">
        <button type="submit" class="btn btn-muted">Export CSV</button>
      </form>
    </form>
  </div>
</div>

<div class="container">
  <aside class="sidebar">
    <h2>Admin Menu</h2>
    <nav>
      <a href="#submissions">Submissions</a>
      <a href="#users">Manage Users</a>
      <a href="#posts">Blog / News</a>
      <a href="#stats">Site Stats</a>
      <a href="#settings">Settings</a>
      <a href="#logout" onclick="alert('Implement logout / auth');">Logout</a>
    </nav>

    <div style="margin-top:16px">
      <div class="small-note">Quick Metrics</div>
      <div style="display:flex;gap:8px;margin-top:8px;flex-direction:column">
        <div class="badge">Total Submissions: <?php echo $total_subs; ?></div>
        <div class="badge">Verified: <?php echo $verified; ?></div>
        <div class="badge">Pending: <?php echo $pending; ?></div>
        <div class="badge">Total Users: <?php echo $total_users; ?></div>
        <div class="badge">Site Views: <?php echo $total_views; ?></div>
        <div class="badge">Budget Sum: <?php echo number_format($total_budget_sum,2); ?></div>
      </div>
    </div>

  </aside>

  <main class="main">
    <div class="cards">
      <div class="card">
        <h3>Total Submissions</h3>
        <p><?php echo $total_subs; ?></p>
      </div>
      <div class="card">
        <h3>Pending Verification</h3>
        <p><?php echo $pending; ?></p>
      </div>
      <div class="card">
        <h3>Verified</h3>
        <p><?php echo $verified; ?></p>
      </div>
      <div class="card">
        <h3>Total Budget</h3>
        <p><?php echo number_format($total_budget_sum,2); ?></p>
      </div>
    </div>

    <div class="layout">

      <!-- LEFT: submissions list -->
      <div class="leftCol">
        <div class="panel submissions-list" id="submissions">
          <h3 style="margin-top:0">Incoming Requests / Messages</h3>
          <div class="small-note">Click any row to view full details and act.</div>

          <table id="subsTable">
            <thead>
              <tr>
                <th>When</th>
                <th>Service</th>
                <th>Name / Contact</th>
                <th>Budget</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($display_submissions as $s): ?>
                <tr data-id="<?php echo safe($s['id']); ?>">
                  <td><?php echo date('Y-m-d H:i', strtotime($s['created_at'] ?? 'now')); ?></td>
                  <td><?php echo safe($s['service_title'] ?? $s['service_key'] ?? ''); ?></td>
                  <td><?php echo safe($s['fullName'] ?? '') . '<br><small class="kv">'.safe($s['email'] ?? '').' · '.safe($s['phone'] ?? '').'</small>'; ?></td>
                  <td><?php echo isset($s['budget']) ? number_format((float)$s['budget'],2) : '-'; ?></td>
                  <td>
                    <?php $st = $s['status'] ?? 'pending_verification';
                      $cls = match($st) {
                        'pending_verification' => 'status-pending',
                        'verified' => 'status-verified',
                        'approved' => 'status-approved',
                        'rejected' => 'status-rejected',
                        default => 'status-pending'
                      };
                    ?>
                    <span class="status-pill <?php echo $cls; ?>"><?php echo safe($st); ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if(empty($display_submissions)): ?>
                <tr><td colspan="5" style="text-align:center;color:var(--muted)">No submissions found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Manage Posts -->
        <div class="panel posts-area" id="posts" style="margin-top:16px">
          <h3>Blog / News — Create Post</h3>
          <form method="POST">
            <input type="hidden" name="action" value="create_post">
            <input type="text" name="post_title" placeholder="Post title" style="margin-bottom:8px">
            <textarea name="post_content" rows="4" placeholder="Post content"></textarea>
            <div style="display:flex;gap:8px;margin-top:8px">
              <button type="submit" class="btn btn-primary">Create Post</button>
              <button type="button" class="btn btn-muted" onclick="window.open('posts_manager.php','_blank')">Open Posts Manager</button>
            </div>
          </form>
        </div>

        <!-- Manage Users -->
        <div class="panel" id="users" style="margin-top:16px">
          <h3>Manage Registered Users</h3>
          <form method="POST" style="display:flex;gap:8px;align-items:center">
            <input type="hidden" name="action" value="add_user">
            <input type="text" name="username" placeholder="Name" required>
            <input type="email" name="useremail" placeholder="Email" required>
            <button type="submit" class="btn btn-primary">Add User</button>
          </form>

          <div style="margin-top:12px">
            <?php if (empty($users)) echo '<div class="small-note">No users yet.</div>'; ?>
            <?php foreach ($users as $u): ?>
              <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f3f6">
                <div>
                  <strong><?php echo safe($u['name'] ?? ''); ?></strong><br>
                  <small class="kv"><?php echo safe($u['email'] ?? ''); ?> · <?php echo safe($u['created_at'] ?? ''); ?></small>
                </div>
                <form method="POST" style="margin:0">
                  <input type="hidden" name="action" value="delete_user">
                  <input type="hidden" name="userid" value="<?php echo safe($u['id']); ?>">
                  <button type="submit" class="btn btn-muted">Delete</button>
                </form>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- RIGHT: detail panel -->
      <aside class="detail-panel">
        <div class="panel" id="detailPanel">
          <h3>Submission Details</h3>
          <div id="detailContent">
            <div class="small-note">Select a submission to view details.</div>
          </div>
          <div class="footer-note">
            <small>Tip: click a row on the left to open full request details, verify identity, and schedule a meeting. Use Export CSV to download all submissions.</small>
          </div>
        </div>

        <div class="panel" style="margin-top:12px">
          <h4>Site Statistics</h4>
          <div class="small-note">Views: <?php echo $total_views; ?></div>
          <div class="small-note">Total users: <?php echo $total_users; ?></div>
          <div style="margin-top:8px">
            <form method="POST" style="display:flex;gap:8px">
              <input type="hidden" name="action" value="clear_views">
              <button type="submit" class="btn btn-muted">Reset Views</button>
            </form>
          </div>
        </div>

      </aside>

    </div>

  </main>
</div>

<!-- messages / errors area -->
<?php if(!empty($messages)): ?>
  <div style="position:fixed;right:18px;bottom:18px;background:#ecfdf5;border:1px solid #bbf7d0;padding:12px;border-radius:8px;">
    <?php foreach($messages as $m) echo "<div style='color:#064e3b'>".safe($m)."</div>"; ?>
  </div>
<?php endif; ?>
<?php if(!empty($errors)): ?>
  <div style="position:fixed;right:18px;bottom:18px;background:#fff1f2;border:1px solid #fecaca;padding:12px;border-radius:8px;">
    <?php foreach($errors as $e) echo "<div style='color:#7f1d1d'>".safe($e)."</div>"; ?>
  </div>
<?php endif; ?>

<script>
// UI: open details when clicking a row
document.querySelectorAll('#subsTable tbody tr[data-id]').forEach(row => {
  row.addEventListener('click', () => {
    const id = row.getAttribute('data-id');
    // When clicked, fetch data from a JS object serialized below
    openDetail(id);
  });
});

// We'll embed the submissions data as JS for client-side detail rendering
const SUBMISSIONS = <?php echo json_encode($submissions, JSON_HEX_TAG|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>;

function openDetail(id) {
  const s = SUBMISSIONS.find(x => x.id === id);
  if (!s) return;
  const container = document.getElementById('detailContent');
  container.innerHTML = '';

  const html = [];
  html.push(`<div style="display:flex;gap:12px;align-items:center"><h4 style="margin:0">${escapeHtml(s.service_title ?? s.service_key ?? '')}</h4><small class="kv" style="margin-left:6px">ID: ${escapeHtml(s.id)}</small></div>`);
  html.push(`<div class="detail-row"><div><strong>Client</strong><br><small class="kv">${escapeHtml(s.fullName ?? '')} · ${escapeHtml(s.email ?? '')}</small></div><div><strong>Budget</strong><br><small>${s.budget ? numberWithCommas(s.budget) : '-'}</small></div></div>`);
  html.push(`<div class="detail-row"><div><strong>Phone</strong><br><small class="kv">${escapeHtml(s.phone ?? '')}</small></div><div><strong>Location</strong><br><small>${escapeHtml(s.location ?? '')}</small></div></div>`);
  html.push(`<div class="detail-row"><div style="flex:1"><strong>Project Details</strong><br><small class="kv">${escapeHtml(s.details ?? s.projectDetails ?? '')}</small></div></div>`);
  html.push(`<div class="detail-row"><div><strong>NID</strong><br><small class="kv">${escapeHtml(s.nid ?? '')}</small></div><div><strong>Submitted</strong><br><small class="kv">${escapeHtml(s.created_at ?? '')}</small></div></div>`);
  // files
  const uploadsDir = 'uploads';
  if (s.nid_scan_file) {
    const f = uploadsDir + '/' + s.nid_scan_file;
    html.push(`<div style="margin-top:8px"><strong>NID Scan:</strong> <a class="uplink" href="${f}" target="_blank">Open</a></div>`);
  }
  if (s.payment_proof_file) {
    const f2 = uploadsDir + '/' + s.payment_proof_file;
    html.push(`<div style="margin-top:8px"><strong>Payment Proof:</strong> <a class="uplink" href="${f2}" target="_blank">Open</a></div>`);
  }

  // meeting
  html.push(`<div style="margin-top:8px"><strong>Meeting</strong><br><small class="kv">${escapeHtml(s.meetingType ?? '')} ${s.appointmentDate ? '· ' + escapeHtml(s.appointmentDate) : ''} ${s.platform ? '· ' + escapeHtml(s.platform) : ''} ${s.meetingDate ? '· ' + escapeHtml(s.meetingDate) : ''}</small></div>`);

  // status & admin actions (form)
  html.push(`<div class="detail-row" style="border-bottom:none;margin-top:12px"><div><strong>Status</strong><br><span class="status-pill ${statusClass(s.status)}">${escapeHtml(s.status || 'pending_verification')}</span></div><div style="text-align:right">`);
  html.push(`<form method="POST" style="display:inline-block;margin-right:8px">
              <input type="hidden" name="action" value="change_status">
              <input type="hidden" name="id" value="${escapeHtml(s.id)}">
              <select name="status" style="padding:6px;border-radius:6px">
                <option value="pending_verification" ${s.status==='pending_verification' ? 'selected' : ''}>Pending</option>
                <option value="verified" ${s.status==='verified' ? 'selected' : ''}>Verified</option>
                <option value="approved" ${s.status==='approved' ? 'selected' : ''}>Approved</option>
                <option value="rejected" ${s.status==='rejected' ? 'selected' : ''}>Rejected</option>
              </select>
              <button type="submit" class="btn btn-primary" style="margin-left:8px">Update</button>
            </form>`);
  html.push(`<form method="POST" style="display:inline-block" onsubmit="return confirm('Delete this submission?');">
              <input type="hidden" name="action" value="delete_submission">
              <input type="hidden" name="id" value="${escapeHtml(s.id)}">
              <button type="submit" class="btn btn-muted">Delete</button>
            </form>`);
  html.push(`</div></div>`);

  container.innerHTML = html.join('');
  // scroll into view
  container.scrollIntoView({behavior:'smooth',block:'start'});
}

function escapeHtml(s){
  if (!s) return '';
  return (''+s).replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;');
}
function numberWithCommas(x){
  if (x === undefined || x === null || x === '') return '-';
  return parseFloat(x).toLocaleString();
}
function statusClass(st){
  if (!st) return 'status-pending';
  if (st === 'pending_verification') return 'status-pending';
  if (st === 'verified') return 'status-verified';
  if (st === 'approved') return 'status-approved';
  if (st === 'rejected') return 'status-rejected';
  return 'status-pending';
}

// small helper: when page loads, if there's a query param id, open it
(function(){
  const params = new URLSearchParams(location.search);
  const id = params.get('id');
  if (id) {
    openDetail(id);
    // highlight the row
    const r = document.querySelector('#subsTable tbody tr[data-id="'+id+'"]');
    if (r) r.scrollIntoView({behavior:'smooth',block:'center'});
  }
})();
</script>
</body>
</html>
