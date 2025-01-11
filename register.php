<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Insert the user details into the database
    $query = "INSERT INTO users (email, password, role, student_id) VALUES (?, ?, 'student', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $hashed_password, $student_id);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful. You can now log in.'); window.location='login.html';</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
