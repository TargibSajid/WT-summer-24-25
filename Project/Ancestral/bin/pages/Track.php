<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Project Dashboard | Ancestral</title>
  <link rel="stylesheet" href="project.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #f4f6f8;
      color: #333;
      font-size: 18px; /* bigger base font */
      line-height: 1.8;
    }

    header {
      background: #1f2937;
      color: white;
      padding: 25px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 22px;
    }

    header h1 {
      margin: 0;
      font-size: 28px;
    }

    header nav a {
      color: white;
      margin-left: 30px;
      text-decoration: none;
      font-weight: bold;
      font-size: 20px;
    }

    .container {
      max-width: 1400px;
      margin: 30px auto;
      padding: 30px;
    }

    .project-summary {
      background: #fff;
      padding: 35px;
      border-radius: 14px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      margin-bottom: 40px;
      font-size: 20px;
    }

    .project-summary h2 {
      margin-bottom: 20px;
      font-size: 26px;
    }

    .progress-bar {
      background: #e5e7eb;
      border-radius: 12px;
      overflow: hidden;
      margin: 20px 0;
      height: 30px;
    }

    .progress {
      background: #3b82f6;
      height: 100%;
      width: 60%; /* example */
      text-align: center;
      color: white;
      font-size: 16px;
      font-weight: bold;
      line-height: 30px;
    }

    .cost-info {
      margin: 15px 0;
      font-size: 20px;
      font-weight: bold;
    }

    .actions {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
    }

    .card {
      flex: 1 1 400px;
      background: #fff;
      border-radius: 14px;
      padding: 30px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.15);
      font-size: 20px;
    }

    .card h3 {
      margin-bottom: 20px;
      font-size: 24px;
    }

    .card textarea,
    .card select,
    .card input {
      width: 100%;
      padding: 14px;
      font-size: 18px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .card button {
      background: #1f2937;
      color: white;
      padding: 14px 25px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
      font-size: 18px;
    }

    .card button:hover {
      background: #374151;
    }

    footer {
      text-align: center;
      padding: 25px;
      background: #1f2937;
      color: white;
      margin-top: 40px;
      font-size: 20px;
    }
  </style>
</head>
<body>

<header>
  <h1>Welcome, Sajid</h1>
  <nav>
    <a href="services.php">➕ New Service</a>
    <a href="logout.php">🚪 Logout</a>
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

  <!-- Actions -->
  <div class="actions">
    
    <!-- Feature Request -->
    <div class="card">
      <h3>📝 Request New Feature</h3>
      <form method="POST" action="feature_request.php">
        <textarea name="feature_details" placeholder="Describe the new feature you want..." rows="5"></textarea>
        <button type="submit">Submit Feature Request</button>
      </form>
    </div>

    <!-- Update Requirements -->
    <div class="card">
      <h3>🔧 Update Requirements</h3>
      <form method="POST" action="update_requirements.php">
        <textarea name="update_details" placeholder="Update your project requirements..." rows="5"></textarea>
        <button type="submit">Submit Update</button>
      </form>
    </div>

    <!-- Request Meeting -->
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
