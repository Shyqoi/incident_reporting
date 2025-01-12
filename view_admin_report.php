<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['report_id'];
    $status = $_POST['status'];
    $comment = $_POST['comment'];

    $updateQuery = "UPDATE reports SET status='$status', comment='$comment' WHERE id='$id'";
    mysqli_query($conn, $updateQuery);

    header("Location: view_admin_report.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        img {
            max-width: 500px;
            max-height: 500px;
            border-radius: 5px;
            object-fit: cover;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            padding: 8px 12px;
            margin: 5px;
            border: 1px solid #007bff;
            text-decoration: none;
            color: #007bff;
            border-radius: 5px;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <a><i class=""></i><span>Welcome, Admin</span></a>
            <a href="admin_dashboard.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
            <a href="view_admin_report.php"><i class="fa fa-eye"></i><span>View and Update Reports</span></a>
            <a href="delete_report.php"><i class="fa fa-trash"></i><span>Delete Reports</span></a>
            <a href="logout.php" class="logout"><i class="fa fa-sign-out"></i><span>Logout</span></a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>View and Update All Reports</h1>
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
                        <th>Admin Comment</th>
                        <th>Update Status</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['time']; ?></td>
                        <td><?php echo $row['gps_location']; ?></td>
                        <td><?php echo $row['details']; ?></td>
                        <td><img src="<?php echo $row['image']; ?>" width="200" height="100" alt="Report Image"></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['comment']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                                <select name="status">
                                    <option value="Pending" <?php if ($row['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Ongoing" <?php if ($row['status'] === 'Ongoing') echo 'selected'; ?>>Ongoing</option>
                                    <option value="Completed" <?php if ($row['status'] === 'Completed') echo 'selected'; ?>>Completed</option>
                                </select>
                                <textarea name="comment" placeholder="Add a comment"><?php echo $row['comment']; ?></textarea>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($totalPages > 1): ?>
                    <br>
                    <div style="text-align: center;">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="view_admin_report.php?page=<?php echo $i; ?>"
                               class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>
