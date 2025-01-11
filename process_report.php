<?php
session_start();
include 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $gps = $_POST['gps'];
    $details = $_POST['details'];
    $image_path = "";

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // Create uploads folder
        }

        $image_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        } else {
            $_SESSION['error'] = "Image upload failed.";
            header("Location: submit_report.php");
            exit();
        }
    }

    // Insert Report into Database
    $stmt = $conn->prepare("INSERT INTO reports (student_id, date, time, gps_location, incident_details, image_path) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $student_id, $date, $time, $gps, $details, $image_path);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Report submitted successfully!";
        header("Location: student_dashboard.php");
    } else {
        $_SESSION['error'] = "Failed to submit report. Please try again.";
        header("Location: submit_report.php");
    }
    $stmt->close();
    $conn->close();
}
?>
