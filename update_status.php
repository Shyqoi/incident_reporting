<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = $_POST['report_id'];
    $status = $_POST['status'];

    $query = "UPDATE reports SET status='$status' WHERE id='$report_id'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Status updated successfully.'); window.location.href = 'admin_dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_dashboard.php");
}
?>
