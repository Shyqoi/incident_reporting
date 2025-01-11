<?php
include 'db.php'; // Connect to your database

// Update existing plain text passwords to hashed versions
$plain_password = "12345"; // The current plain text password in your database
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT); // Hash the password

// SQL query to update the password
$query = "UPDATE users SET password = ? WHERE password = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $hashed_password, $plain_password);

// Execute the query
if ($stmt->execute()) {
    echo "Passwords updated successfully!";
} else {
    echo "Error updating passwords: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
