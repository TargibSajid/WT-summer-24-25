<?php




session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} elseif (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
}





?>







<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Project Dashboard | Ancestral</title>
  <link rel = "stylesheet"  type = "text/css" href = "../../cascade/Track.css? <?php echo time(); ?>">
</head>
<body>

<header>
  <h1>Welcome, <?php echo $username ?></h1>
  <nav>
    <a href="../Service.php">➕ New Service</a>
    <a href="../logout.php">
    <img id ="log" src="../../../Logout2.png" alt="icon" style="width:25px; height:25px; vertical-align:left;">  
    
    Logout</a>
  </nav>
</header>

<div class="container">
  
  <!-- Project Summary -->
  <div class="project-summary">
    <h2>📌 Current Project: Artificial Intelligence Service</h2>
    <p>Status: <strong style="color: green;">In Progress</strong></p>
    <div class="progress-bar">
      <div class="progress" style="width: 60%;">60% Complete</div>
    </div>
    <p class="cost-info">💰 Total Cost: $5000 | Paid: $2500 | Remaining: $2500</p>
    <p>📅 Estimated Completion: <strong>2025-12-10</strong></p>
  </div>

  
  <div class="actions">
    
    
    <div class="card">
      <h3>📝 Request New Feature</h3>
      <form method="POST" action="feature_request.php">
        <textarea name="feature_details" placeholder="Describe the new feature you want..." rows="5"></textarea>
        <button type="submit">Submit Feature Request</button>
      </form>
    </div>

    
    <div class="card">
      <h3>🔧 Update Requirements</h3>
      <form method="POST" action="update_requirements.php">
        <textarea name="update_details" placeholder="Update your project requirements..." rows="5"></textarea>
        <button type="submit">Submit Update</button>
      </form>
    </div>

    
    <div class="card">
      <h3>📅 Organize Meeting</h3>
      <form method="POST" action="request_meeting.php">
        <label for="meeting_type">Meeting Type:</label>
        <select name="meeting_type" id="meeting_type">
          <option value="online">💻 Online (Zoom, Skype, Discord)</option>
          <option value="face-to-face">🏢 Face-to-Face</option>
        </select>
        <input type="date" name="meeting_date" required>
        <input type="time" name="meeting_time" required>
        <button type="submit">Request Meeting</button>
      </form>
    </div>

  </div>

</div>

<footer>
  <p>&copy; 2025 Ancestral | Project Tracking Portal</p>
</footer>

</body>
</html>
