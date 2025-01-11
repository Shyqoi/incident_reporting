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

    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 60px;
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            transition: width 0.3s;
            overflow: hidden;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        /* Sidebar expanded */
        .sidebar:hover {
            width: 200px;
        }

        /* Sidebar button */
        .sidebar .menu-icon {
            text-align: center;
            font-size: 24px;
            padding: 15px;
            cursor: pointer;
            display: block;
        }

        .sidebar:hover .menu-icon {
            display: none;
        }

        /* Sidebar links */
        .sidebar a {
            display: none;
            color: white;
            text-decoration: none;
            padding: 15px;
            text-align: left;
        }

        .sidebar:hover a {
            display: block;
        }

        .sidebar a:hover {
            background-color: #1a252f;
        }

        .logout {
            background-color: red;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 60px;
            flex-grow: 1;
            padding: 20px;
            text-align: center;
            transition: margin-left 0.3s ease-in-out;
        }

        /* When sidebar expands */
        .sidebar:hover + .main-content {
            margin-left: 200px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }

        /* Pagination */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #007bff;
            color: #007bff;
            margin: 2px;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }
    </style>

    <script>
        $(document).ready(function () {
            $(".sidebar").hover(
                function () {
                    $(this).css("width", "200px");
                    $(".menu-icon").hide();
                    $(".main-content").css("margin-left", "200px");
                },
                function () {
                    $(this).css("width", "60px");
                    $(".menu-icon").show();
                    $(".main-content").css("margin-left", "60px");
                }
            );
        });
    </script>

</head>
<body>

<div class="container">
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <span class="menu-icon">⚙</span> <!-- Gear Icon when sidebar is closed -->
        <a href="student_dashboard.php"><i class="fa fa-dashboard"></i><span> Dashboard</span></a>
        <a href="submit_report.php"><i class="fa fa-file"></i><span> Submit New Report</span></a>
        <a href="view_report.php"><i class="fa fa-eye"></i><span> View My Reports</span></a>
        <a href="view_all_report.php"><i class="fa fa-eye"></i><span> View All Reports</span></a>
        <a href="edit_profile.php"><i class="fa fa-user"></i><span> Edit Profile</span></a>
        <a href="logout.php" class="logout"><i class="fa fa-sign-out"></i><span> Logout</span></a>
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
                    <td><img src="<?php echo $row['image']; ?>" alt="Report Image"></td>
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
