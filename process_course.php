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
	$name = $_POST["name"];
	$name = htmlspecialchars($name); // Prevent XSS attacks
	
	// File upload
	$targetDirectory = "assets/"; // Directory to store uploaded images
	$targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	if($check !== false) {
		$uploadOk = 1;
	} else {
		echo "Error: File is not an image.";
		$uploadOk = 0;
	}

	// Check if file already exists
	if (file_exists($targetFile)) {
		echo "Error: File already exists.";
		$uploadOk = 0;
	}

	// Check file size
	if ($_FILES["image"]["size"] > 500000) {
		echo "Error: File is too large.";
		$uploadOk = 0;
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		echo "Error: Only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Error: File was not uploaded.";
	} else {
		// Move uploaded file to target directory
		if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
			// Insert data into database
			$imagePath = $targetFile;
			$sql = "INSERT INTO courses (name, image_path) VALUES ('$name', '$imagePath')";
			if ($conn->query($sql) === TRUE) {
				echo "Course added successfully. <br>";
				echo '<a href="index.php" class="btn btn-primary">Back to Courses</a>';
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		} else {
			echo "Error: There was an error uploading your file.";
		}
	}
}

// Close connection
$conn->close();
?>
