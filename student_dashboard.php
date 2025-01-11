<?php
session_start();
include 'db.php'; // Ensure this file exists and is correctly set up

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Pagination settings
$limit = 5; // Number of reports per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch all reports with pagination
$query = "SELECT * FROM reports ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query) or die("Query failed: " . mysqli_error($conn));

// Count total records
$totalQuery = "SELECT COUNT(*) AS total FROM reports";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalPages = ceil($totalRow['total'] / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Ensure this file exists -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".sidebar").hover(
                function() {
                    $(this).css("width", "200px");
                    $(".sidebar a span").css("display", "inline");
                },
                function() {
                    $(this).css("width", "60px");
                    $(".sidebar a span").css("display", "none");
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
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar:hover + .main-content {
            margin-left: 200px;
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
		<a><i class=""></i><span>Welcome , <?php echo $student_id ?></span></a>
        <a href="student_dashboard.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
        <a href="submit_report.php"><i class="fa fa-file"></i><span>Submit New Report</span></a>
        <a href="view_report.php"><i class="fa fa-eye"></i><span>View My Reports</span></a>
        <a href="view_all_report.php"><i class="fa fa-eye"></i><span>View All Reports</span></a>
        <a href="edit_profile.php"><i class="fa fa-user"></i><span>Edit Profile</span></a>
        <a href="logout.php" class="logout"><i class="fa fa-sign-out"></i><span>Logout</span></a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>All Student Reports</h2>

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
                <th>Admin Comment</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['time']; ?></td>
                    <td><?php echo $row['gps_location']; ?></td>
                    <td><?php echo $row['details']; ?></td>
                    <td><img src="<?php echo $row['image']; ?>" alt="Report Image" width="100"></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo isset($row['comment']) ? $row['comment'] : 'Waiting for admin to comment'; ?></td>
                </tr>
            <?php } ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <a href="student_dashboard.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</body>
</html>
