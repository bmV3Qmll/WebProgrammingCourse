<?php
$imageOk = 1;

if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
	// File upload
	$targetDirectory = "assets/"; // Directory to store uploaded images
	$targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
	$imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	if($check !== false) {
		$imageOk = 1;
	} else {
		echo "Error: File is not an image. <br>";
		$imageOk = 0;
	}

	// Check if file already exists
	if (file_exists($targetFile)) {
		echo "Error: File already exists. <br>";
		$imageOk = 0;
	}

	// Check file size
	if ($_FILES["image"]["size"] > 500000) {
		echo "Error: File is too large. <br>";
		$imageOk = 0;
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		echo "Error: Only JPG, JPEG, PNG & GIF files are allowed. <br>";
		$imageOk = 0;
	}

	// Check if $imageOk is set to 0 by an error
	if ($imageOk == 1) {
		// Move uploaded file to target directory
		if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
			// Insert data into database
			$imagePath = $targetFile;
		} else {
			$imageOk = 0;
			echo "Error: There was an error uploading your file. <br>";
		}
	}
}
?>