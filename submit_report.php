<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve submitted form data
    $student_id = $_SESSION['student_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $gps_location = $_POST['gps_location'];
    $details = $_POST['details'];
    
    // Handle file upload
    $image = $_FILES['image'];
    $image_name = '';
    
    if ($image['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
        }
        
        $image_name = $target_dir . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $image_name);
    }

    // SQL Query to insert report into database
    $query = "INSERT INTO reports (student_id, date, time, gps_location, details, image, status) 
              VALUES ('$student_id', '$date', '$time', '$gps_location', '$details', '$image_name', 'Pending')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Report submitted successfully!'); window.location.href='student_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error submitting report. Please try again!'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit New Incident Report</title>
    <link rel="stylesheet" href="style1.css">
	<nav style="position: absolute; top: 100px; right: 50px;">
    <a href="student_dashboard.php" 
       style="margin-right: 15px; font-size: 2rem; text-decoration: none; 
              color: white; background-color: #5C808D; padding: 10px 20px; 
              border-radius: 5px; box-shadow: 0px 2px 5px rgba(0,0,0,0.3);">
        Home
    </a>
</nav>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBco9GGqtl8g3sq_vk6hTstKKCFrhJCccw"></script>
    <script>
        let map;
        let marker;

        function initMap() {
            const initialLocation = { lat: 3.1390, lng: 101.6869 }; // Default location (Kuala Lumpur, for example)
            map = new google.maps.Map(document.getElementById("map"), {
                center: initialLocation,
                zoom: 15,
            });

            marker = new google.maps.Marker({
                position: initialLocation,
                map: map,
                draggable: true,
            });

            google.maps.event.addListener(marker, 'position_changed', function () {
                const position = marker.getPosition();
                document.getElementById('gps_location').value = position.lat() + ", " + position.lng();
            });

            map.addListener("click", function (event) {
                marker.setPosition(event.latLng);
            });
        }
    </script>
</head>
<body onload="initMap()">
    <h1>Submit New Incident Report</h1>
    <form action="submit_report.php" method="POST" enctype="multipart/form-data">
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" min="<?php echo date('2025-01-11'); ?>" required>

        <label for="time">Time:</label>
        <input type="time" name="time" id="time" required>

        <label for="gps_location">GPS Location:</label>
        <input type="text" name="gps_location" id="gps_location" placeholder="Select location on the map" readonly required>
        <div id="map" style="width: 100%; height: 400px; margin-bottom: 20px;"></div>

        <label for="details">Incident Details:</label>
        <textarea name="details" id="details" rows="5" placeholder="Describe the incident..." required></textarea>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" accept="image/*">

        <button type="submit">Submit Report</button>
    </form>
    <a href="student_dashboard.php" class="back-btn">Back</a>
</body>
</html>
