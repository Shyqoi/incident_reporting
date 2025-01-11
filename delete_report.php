<?php
session_start();
include 'db.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

// Fetch all reports
$query = "SELECT * FROM reports";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Report</title>
    <link rel="stylesheet" href="style.css">
	<nav style="position: absolute; top: 100px; right: 50px;">
    <a href="admin_dashboard.php" 
       style="margin-right: 15px; font-size: 2rem; text-decoration: none; 
              color: white; background-color: #5C808D; padding: 10px 20px; 
              border-radius: 5px; box-shadow: 0px 2px 5px rgba(0,0,0,0.3);">
        Home
    </a>
</nav>
	<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h1 {
            margin: 20px 0;
            color: #333;
        }

        .table-container {
            margin: 20px auto;
            width: 90%;
            overflow-x: auto;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #dddddd;
        }

        th {
            background-color: #007bff;
            color: white;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .back-link {
            margin: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <h1>Select a Report to Delete</h1>
	<div class="table-container">
    <table>
        <tr>
            <th>Report ID</th>
            <th>Student ID</th>
            <th>Date</th>
            <th>Time</th>
            <th>GPS Location</th>
            <th>Details</th>
            <th>Image</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['student_id']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['time']; ?></td>
            <td><?php echo $row['gps_location']; ?></td>
            <td><?php echo $row['details']; ?></td>
            <td><img src="<?php echo $row['image']; ?>" width="100" alt="Report Image"></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <form method="POST" action="process_delete_report.php">
                    <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
	</div>
    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
