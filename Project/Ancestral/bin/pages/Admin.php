
<?php

session_start();



?>


<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Ancestral Admin Dashboard</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="../cascade/Admin.css? <?php echo time(); ?>"

</head>

<body>

<!-- Header -->
<div class="header">
  <div class="brand">
    <img src="../../Logo.svg" alt="logo">
    <h1>Ancestral Admin</h1>
  </div>
  <div style="display:flex;gap:8px;align-items:center">
    <input type="text" placeholder="Search submissions, names, email...">
    <select>
      <option>All services</option>
      <option>Web Development</option>
      <option>AI Solutions</option>
    </select>
    <select>
      <option>All statuses</option>
      <option>Pending</option>
      <option>Verified</option>
      <option>Approved</option>
      <option>Rejected</option>
    </select>
    <button class="btn btn-primary">Filter</button>
    <button class="btn btn-muted">Export CSV</button>
  </div>
</div>

<div class="container">
  <aside class="sidebar">
    <h2>Admin Menu</h2>
    <nav>
      <a href="#submissions">Submissions</a>

      <a href="#logout">Logout</a>
    </nav>
    <div style="margin-top:16px">
      <div class="small-note">Quick Metrics</div>
      <div style="display:flex;gap:8px;margin-top:8px;flex-direction:column">
        <div class="badge">Total Submissions: 25</div>
        <div class="badge">Verified: 10</div>
        <div class="badge">Pending: 12</div>
        <div class="badge">Total Users: 8</div>
        <div class="badge">Site Views: 523</div>
        <div class="badge">Budget Sum: 50,000.00</div>
      </div>
    </div>
  </aside>

  <main class="main">
    <div class="cards">
      <div class="card"><h3>Total Submissions</h3><p>25</p></div>
      <div class="card"><h3>Pending Verification</h3><p>12</p></div>
      <div class="card"><h3>Verified</h3><p>10</p></div>
      <div class="card"><h3>Total Budget</h3><p>50,000.00</p></div>
    </div>

    <div class="layout">
      <!-- LEFT -->
      <div class="leftCol">
        <div class="panel submissions-list" id="submissions">
          <h3>Incoming Requests / Messages</h3>
          <div class="small-note">Click any row to view full details and act.</div>
          <table>
            <thead>
              <tr><th>When</th><th>Service</th><th>Name / Contact</th><th>Budget</th><th>Status</th></tr>
            </thead>
            <tbody>
              <tr>
                <td>2025-09-14 10:15</td>
                <td>Web Development</td>
                <td>John Doe<br><small class="kv">john@example.com · 0123456789</small></td>
                <td>10,000.00</td>
                <td><span class="status-pill status-pending">pending_verification</span></td>
              </tr>
              <tr>
                <td>2025-09-13 16:40</td>
                <td>AI Solutions</td>
                <td>Mary Jane<br><small class="kv">mary@example.com · 0170000000</small></td>
                <td>15,500.00</td>
                <td><span class="status-pill status-verified">verified</span></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="panel posts-area" id="posts" style="margin-top:16px">
          <h3>Blog / News — Create Post</h3>
          <input type="text" placeholder="Post title" style="margin-bottom:8px">
          <textarea rows="4" placeholder="Post content"></textarea>
          <div style="display:flex;gap:8px;margin-top:8px">
            <button class="btn btn-primary">Create Post</button>
            <button class="btn btn-muted">Open Posts Manager</button>
          </div>
        </div>

        <div class="panel" id="users" style="margin-top:16px">
          <h3>Manage Registered Users</h3>
          <div style="display:flex;gap:8px;align-items:center">
            <input type="text" placeholder="Name">
            <input type="email" placeholder="Email">
            <button class="btn btn-primary">Add User</button>
          </div>
          <div style="margin-top:12px">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f3f6">
              <div><strong>Jane Smith</strong><br><small class="kv">jane@example.com · 2025-09-01</small></div>
              <button class="btn btn-muted">Delete</button>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT -->
      <aside class="detail-panel">
        <div class="panel" id="detailPanel">
          <h3>Submission Details</h3>
          <div class="small-note">Select a submission to view details.</div>
          <div class="footer-note">
            <small>Tip: click a row on the left to open full request details, verify identity, and schedule a meeting.</small>
          </div>
        </div>
        <div class="panel" style="margin-top:12px">
          <h4>Site Statistics</h4>
          <div class="small-note">Views: 523</div>
          <div class="small-note">Total users: 8</div>
          <div style="margin-top:8px"><button class="btn btn-muted">Reset Views</button></div>
        </div>
      </aside>
    </div>
  </main>
</div>
</body>
</html>
