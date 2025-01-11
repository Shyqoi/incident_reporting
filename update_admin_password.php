<?php
include 'db.php'; // Connect to your database

// Define the admin's plain text password (replace 'admin123' with the correct password)
$admin_plain_password = "admin123";
$hashed_password = password_hash($admin_plain_password, PASSWORD_DEFAULT);

// Update admin's password in the database
$query = "UPDATE users SET password = ? WHERE email = 'admin@example.com'";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $hashed_password);

// Execute the query
if ($stmt->execute()) {
    echo "Admin password updated successfully!";
} else {
    echo "Error updating admin password: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
