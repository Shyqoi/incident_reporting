<?php
session_start();
include 'db.php'; // Database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_SESSION['student_id'];
    $datetime = $_POST['datetime'];
    $location = $_POST['location'];

    // Handle the image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Insert report into the database
    $query = "INSERT INTO reports (student_id, image, datetime, location) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $student_id, $target_file, $datetime, $location);

    if ($stmt->execute()) {
        echo "<script>alert('Report submitted successfully!'); window.location.href='student_dashboard.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Report</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Submit New Report</h2>
    <form action="new_report.php" method="POST" enctype="multipart/form-data">
        <label for="image">Upload Picture:</label>
        <input type="file" name="image" required><br><br>

        <label for="datetime">Date & Time:</label>
        <input type="datetime-local" name="datetime" required><br><br>

        <label for="location">GPS Location:</label>
        <input type="text" name="location" placeholder="Enter GPS coordinates or location" required><br><br>

        <button type="submit">Submit Report</button>
    </form>
    <a href="student_dashboard.php"><button>Back</button></a>
</body>
</html>
