<?php
session_start();
include 'db.php'; // Include database connection

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
	<div class="logo-container" style="position: absolute; top: 10px; left: 10px; display: flex; align-items: center; gap: 10px;">
    <img src="unitenlogo.png" style="max-height: 235px; width: auto;" alt="UNITEN Logo">
    <span style="font-size: 2.0rem; font-weight: bold; color: #6E99A5;">ONLINE INCIDENT REPORTING SYSTEM FOR CENDIKIAWAN</span>
	</div>
	
	<nav style="position: absolute; top: 100px; right: 50px;">
    <a href="admin_dashboard.php" 
       style="margin-right: 15px; font-size: 2rem; text-decoration: none; 
              color: white; background-color: #5C808D; padding: 10px 20px; 
              border-radius: 5px; box-shadow: 0px 2px 5px rgba(0,0,0,0.3);">
        Home
    </a>
</nav>
</head>
<body>
    <h1>Welcome, Admin</h1>
	<h2>Admin Dashboard</h2>
    <div class="button-container">
        <button onclick="location.href='view_admin_report.php'">View and Update Reports</button>
		<button onclick="location.href='delete_report.php'">Delete Reports</button>
        <button onclick="location.href='logout.php'">Logout</button>
    </div>
</body>
</html>
