<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch completed reports for the logged-in student
$query = "SELECT * FROM reports WHERE student_id = ? AND status = 'completed'";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Completed Reports</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Your Completed Reports</h2>

    <!-- Table to Display Completed Reports -->
    <table border="1" cellspacing="0" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Date & Time</th>
            <th>Location</th>
            <th>Status</th>
        </tr>
        <?php
        // Check if there are any completed reports
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td><img src='" . $row['image'] . "' width='100' height='100'></td>";
                echo "<td>" . $row['datetime'] . "</td>";
                echo "<td>" . $row['location'] . "</td>";
                echo "<td>" . ucfirst($row['status']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No completed reports found.</td></tr>";
        }
        ?>
    </table>

    <!-- Back Button -->
    <a href="student_dashboard.php"><button>Back to Dashboard</button></a>
</body>
</html>
