<?php
include 'nav_bar.php';
if(!isset($_SESSION["uid"])) {
	header("Location: login.php");
	exit();
}
?>
<div class="container">
	<h1>Add Course</h1>
	<?php
	// Process form submission
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include 'connect_db.php';
		// Validate input
		$name = trim($_POST["name"]);
		$name = htmlspecialchars($name); // Prevent XSS attacks
		$imagePath = "assets/blank.jpg";
		$stat = 0;
		echo '<div id="messageBox" class="alert alert-info message-box" role="alert">';
		include 'upload_image.php';
		if ($imageOk == 1) {
			$uid = $_SESSION["uid"];
			$sql = "INSERT INTO courses (name, image_path, uid) VALUES ('$name', '$imagePath', '$uid')";

			try {
				$conn->query($sql);
				echo 'Course added successfully. <br>';
				$stat = 1;
			} catch (mysqli_sql_exception $e) {
				if ($e->getCode() == 1062) {
				    echo "Duplicated course name. <br>";
				} else {
					echo "Insertion error:" . $sql . " <br>";
				}
			}
		}
		echo '</div>';
		echo '<script>showMessage(' . $stat . ')</script>';
		$conn->close();
	}
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