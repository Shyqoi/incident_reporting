<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Student Reports</title>
    <link rel="stylesheet" href="style.css">
	<nav style="position: absolute; top: 100px; right: 50px;">
    <a href="student_dashboard.php" 
       style="margin-right: 15px; font-size: 2rem; text-decoration: none; 
              color: white; background-color: #5C808D; padding: 10px 20px; 
              border-radius: 5px; box-shadow: 0px 2px 5px rgba(0,0,0,0.3);">
        Home
    </a>
</nav>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h1 {
            margin: 20px 0;
            color: #333;
        }

        .table-container {
            margin: 20px auto;
            width: 90%;
            overflow-x: auto;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #dddddd;
        }

        th {
            background-color: #007bff;
            color: white;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .back-link {
            margin: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <h1>All Student Reports</h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Student ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>GPS Location</th>
                    <th>Details</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Admin Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'db.php';
                $query = "SELECT * FROM reports";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gps_location']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['details']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($row['image']) . "' alt='Report Image'></td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['comment']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <a href="student_dashboard.php" class="back-button">Back to Dashboard</a>
</body>
</html>
