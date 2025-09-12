<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Ancestral</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #333;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #1f2937;
            color: #fff;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 22px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            display: block;
            padding: 10px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            background: #374151;
        }

        /* Main content */
        .main {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .topbar input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 250px;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 22px;
            font-weight: bold;
        }

        /* Table */
        .recent-activity {
            margin-top: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f1f1f1;
        }

        .quick-actions {
            margin-top: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        .quick-actions button {
            background: #1f2937;
            color: white;
            padding: 10px 20px;
            border: none;
            margin: 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .quick-actions button:hover {
            background: #374151;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Ancestral Admin</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Manage Posts</a></li>
            <li><a href="#">Manage Users</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="topbar">
            <h1>Dashboard</h1>
            <input type="text" placeholder="Search...">
        </div>

        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Users</h3>
                <p>1,245</p>
            </div>
            <div class="card">
                <h3>Total Posts</h3>
                <p>87</p>
            </div>
            <div class="card">
                <h3>Messages</h3>
                <p>342</p>
            </div>
            <div class="card">
                <h3>Revenue</h3>
                <p>$12,450</p>
            </div>
        </div>

        <div class="recent-activity">
            <h2>Recent Activity</h2>
            <table>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Date</th>
                </tr>
                <tr>
                    <td>Sajid</td>
                    <td>Added a new blog post</td>
                    <td>2025-09-09</td>
                </tr>
                <tr>
                    <td>Admin</td>
                    <td>Updated settings</td>
                    <td>2025-09-08</td>
                </tr>
            </table>
        </div>

        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <button>Add Post</button>
            <button>Add User</button>
            <button>View Reports</button>
        </div>
    </div>

</body>
</html>
