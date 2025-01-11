<?php
session_start();
include 'db.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

// Check if the report ID is provided
if (isset($_POST['report_id'])) {
    $report_id = $_POST['report_id'];

    // Delete the report from the database
    $query = "DELETE FROM reports WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $report_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Report deleted successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete the report.";
    }

    $stmt->close();
}

header("Location: delete_report.php");
exit();
?>
