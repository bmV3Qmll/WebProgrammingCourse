<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assignment";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Validate input
	$name = trim($_POST["name"]);
	$name = htmlspecialchars($name); // Prevent XSS attacks
	$imagePath = "assets/blank.jpg";
	include 'upload_image.php';
	if ($imageOk == 1) {
		$sql = "INSERT INTO courses (name, image_path) VALUES ('$name', '$imagePath')";
		try {
			$conn->query($sql);
			echo "Course added successfully. <br>";
		} catch (mysqli_sql_exception $e) {
			if ($e->getCode() == 1062) {
			    echo "Duplicate course name. <br>";
			} else {
				echo "Insertion error:" .$query. "<br>";
			}
		}
	}
	echo '<a href="index.php" class="btn btn-primary">Back to Courses</a>';
}

// Close connection
$conn->close();
?>
