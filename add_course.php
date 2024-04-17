<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Course</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
	<h1>Add Course</h1>
	<?php
	// Database connection
	include 'connect_db.php';

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
				    echo "Duplicated course name. <br>";
				} else {
					echo "Insertion error:" .$query. "<br>";
				}
			}
		}
	}

	// Close connection
	$conn->close();
	?>
	<form action="" method="POST" enctype="multipart/form-data">
		<div class="form-group">
			<label for="name">Course Name:</label>
			<input type="text" class="form-control" id="name" name="name" required>
		</div>
		<div class="form-group">
			<label for="image">Upload Image:</label>
			<input type="file" class="form-control-file" id="image" name="image" accept="image/*">
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>

</body>
</html>
