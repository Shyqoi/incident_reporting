<?php
session_start();
include 'db.php'; // Include database connection

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Pagination settings
$limit = 5; // Number of reports per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search filters
$searchVenue = isset($_GET['venue']) ? $_GET['venue'] : "";
$searchDetails = isset($_GET['details']) ? $_GET['details'] : "";
$searchMonth = isset($_GET['month']) ? $_GET['month'] : "";

// Query to fetch reports based on search criteria
$query = "SELECT * FROM reports WHERE 1";

if (!empty($searchVenue)) {
    $query .= " AND venue = '$searchVenue'";
}
if (!empty($searchDetails)) {
    $query .= " AND details LIKE '%$searchDetails%'";
}
if (!empty($searchMonth)) {
    $query .= " AND DATE_FORMAT(date, '%Y-%m') = '$searchMonth'";
}

$query .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query) or die("Query failed: " . mysqli_error($conn));

// Count total records (for pagination)
$totalQuery = "SELECT COUNT(*) AS total FROM reports WHERE 1";
if (!empty($searchVenue)) {
    $totalQuery .= " AND venue = '$searchVenue'";
}
if (!empty($searchDetails)) {
    $totalQuery .= " AND details LIKE '%$searchDetails%'";
}
if (!empty($searchMonth)) {
    $totalQuery .= " AND DATE_FORMAT(date, '%Y-%m') = '$searchMonth'";
}

$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalPages = ceil($totalRow['total'] / $limit);
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
        .search-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
            padding: 20px;
            border-radius: 10px;
        }
        .search-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 81%;
        }
        .search-container input, .search-container select, .search-container button {
            padding: 8px;
            margin: 5px;
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
            <!-- Search Form -->
            <div class="search-container">
                <form method="GET" action="admin_dashboard.php">
                    <label for="venue">Venue:</label>
                    <select name="venue">
                        <option value="">All</option>
                        <option value="stairs" <?php if ($searchVenue == "stairs") echo "selected"; ?>>Stairs</option>
                        <option value="lift" <?php if ($searchVenue == "lift") echo "selected"; ?>>Lift</option>
                        <option value="room" <?php if ($searchVenue == "room") echo "selected"; ?>>Room</option>
                        <option value="others" <?php if ($searchVenue == "others") echo "selected"; ?>>Others</option>
                    </select>

                    <label for="details">Keyword:</label>
                    <input type="text" name="details" placeholder="Enter keyword..." value="<?php echo $searchDetails; ?>">

                    <label for="month">Month:</label>
                    <input type="month" name="month" value="<?php echo $searchMonth; ?>">

                    <button type="submit">Search</button>
                    <a href="admin_dashboard.php"><button type="button">Reset</button></a>
                </form>
            </div>

            <h2>All Reports</h2>
            <table>
                <tr>
                    <th>Report ID</th>
                    <th>Student ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>GPS Location</th>
                    <th>Venue</th> <!-- Added Venue Column -->
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
                        <td><?php echo $row['venue']; ?></td> <!-- Added Venue Data -->
                        <td><?php echo $row['details']; ?></td>
                        <td><img src="<?php echo $row['image']; ?>" alt="Report Image" width="100"></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo isset($row['comment']) ? $row['comment'] : 'Waiting for admin to comment'; ?></td>
                    </tr>
                <?php } ?>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($totalPages > 1): ?>
                    <br>
                    <div style="text-align: center;">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="admin_dashboard.php?page=<?php echo $i; ?>&venue=<?php echo $searchVenue; ?>&details=<?php echo $searchDetails; ?>&month=<?php echo $searchMonth; ?>"
                               class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>

            <style>
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
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>
