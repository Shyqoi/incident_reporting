<?php
session_start();
include 'db.php'; // Ensure this file exists and is properly set up

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student name (assuming you have a table 'students' with 'name' column)
$student_query = "SELECT name FROM students WHERE id = '$student_id'";
$student_result = mysqli_query($conn, $student_query);
$student_row = mysqli_fetch_assoc($student_result);
$student_name = $student_row['name'];

// Fetch reports submitted by the logged-in student
$query = "SELECT * FROM reports WHERE student_id = '$student_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query) or die("Query failed: " . mysqli_error($conn));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View My Reports</title>
    <link rel="stylesheet" href="style.css"> <!-- Ensure this file exists -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".sidebar").hover(
                function() {
                    $(this).css("width", "200px");
                    $(".sidebar a span, .sidebar .welcome, .sidebar .logo").css("display", "inline");
                },
                function() {
                    $(this).css("width", "60px");
                    $(".sidebar a span, .sidebar .welcome, .sidebar .logo").css("display", "none");
                }
            );
        });
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 60px;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
            height: 100vh;
            transition: width 0.3s;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .sidebar:hover {
            width: 200px;
        }
        .sidebar .logo {
            display: none;
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar .logo img {
            width: 40px;
        }
        .sidebar .welcome {
            display: none;
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 15px;
            text-align: left;
        }
        .sidebar a:hover {
            background-color: #1a252f;
        }
        .sidebar a span {
            display: none;
            margin-left: 10px;
        }
        .logout {
            background-color: red;
            text-align: center;
        }
        .main-content {
            margin-left: 60px;
            flex-grow: 1;
            padding: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="logo">
            <img src="path/to/uniten_logo.png" alt="Uniten Logo">
        </div>
        <div class="welcome">
            Welcome, <?php echo htmlspecialchars($student_name); ?>
        </div>
        <a href="student_dashboard.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
        <a href="submit_report.php"><i class="fa fa-file"></i><span>Submit New Report</span></a>
        <a href="view_all_report.php"><i class="fa fa-eye"></i><span>View All Reports</span></a>
        <a href="edit_profile.php"><i class="fa fa-user"></i><span>Edit Profile</span></a>
        <a href="logout.php" class="logout"><i class="fa fa-sign-out"></i><span>Logout</span></a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Your Submitted Reports</h2>

        <table>
            <tr>
                <th>Report ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>GPS Location</th>
                <th>Details</th>
                <th>Image</th>
                <th>Status</th>
                <th>Admin Comment</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['time']; ?></td>
                    <td><?php echo $row['gps_location']; ?></td>
                    <td><?php echo $row['details']; ?></td>
                    <td><img src="<?php echo $row['image']; ?>" alt="Report Image" width="100"></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo !empty($row['admin_comment']) ? $row['admin_comment'] : "Waiting for admin to comment"; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</body>
</html>
