<?php
session_start();
include 'db.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$message = "";

// Fetch user information
$query = "SELECT * FROM users WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $update_query = "UPDATE users SET password = ? WHERE student_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ss", $password, $student_id);

        if ($stmt->execute()) {
            $message = "Password updated successfully.";
        } else {
            $message = "Failed to update password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
	<nav style="position: absolute; top: 100px; right: 50px;">
    <a href="student_dashboard.php" 
       style="margin-right: 15px; font-size: 2rem; text-decoration: none; 
              color: white; background-color: #5C808D; padding: 10px 20px; 
              border-radius: 5px; box-shadow: 0px 2px 5px rgba(0,0,0,0.3);">
        Home
    </a>
</nav>
</head>
<body>
    <h1>Edit Profile</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" disabled>

        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" value="<?php echo $user['student_id']; ?>" disabled>

        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Update Password</button>
    </form>
    <br>
    <a href="student_dashboard.php">Back to Dashboard</a>
</body>
</html>
