<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>View Course</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<!-- Chart.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
	<!-- Custom CSS -->
	<style>
		body {
			background-color: #f8f9fa;
			background-size: cover;
			background-repeat: no-repeat;
			background-attachment: fixed;
			padding-top: 50px;
		}

		.container {
			background-color: rgba(255, 255, 255, 0.8); /* Set container background color with transparency */
			padding: 20px;
			border-radius: 10px;
		}

		.test-card {
			margin-bottom: 20px;
		}
	</style>
</head>
<body>
	<div class="container">
	<?php
	// Database connection
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "assignment";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$courseName = $_GET['name'];
	/*
	$sqlImagePath = "SELECT image_path FROM courses WHERE name = '$courseName'";
	$resultImagePath = $conn->query($sqlImagePath);
	if ($resultImagePath->num_rows > 0) {
	    $rowImagePath = $resultImagePath->fetch_assoc();
	    $courseImagePath = $rowImagePath['image_path'];
	} else {
	    $courseImagePath = 'assets/blank.jpg'; // Set default image path
	}

	echo "<body style=\"background-image: url('" . $courseImagePath . "');\"";
	*/
	echo '<div class="container">';
	echo '<h1>' . $courseName . '</h1>';

	// Retrieve tests for the course
	$sql = "SELECT title, no_easy, no_medium, no_hard FROM tests WHERE cid IN (SELECT cid FROM courses WHERE name = '$courseName')";

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$title = $row['title'];
			$noEasy = $row['no_easy'];
			$noMedium = $row['no_medium'];
			$noHard = $row['no_hard'];

			// Calculate total questions
			$totalQuestions = $noEasy + $noMedium + $noHard;

			// Calculate percentage of each difficulty level
			$percentEasy = round(($noEasy / $totalQuestions) * 100);
			$percentMedium = round(($noMedium / $totalQuestions) * 100);
			$percentHard = round(($noHard / $totalQuestions) * 100);

			echo '<div class="test-card">';
			echo '<h3>' . $title . ' - Number of Questions: ' . $totalQuestions . '</h3>';
			echo '<canvas id="chart_' . $title . '" width="400" height="400"></canvas>';
			echo '</div>';

			// JavaScript to create pie chart
			echo "<script>
					var ctx = document.getElementById('chart_$title').getContext('2d');
					var myChart = new Chart(ctx, {
						type: 'pie',
						data: {
							labels: ['Easy', 'Medium', 'Hard'],
							datasets: [{
								label: 'Difficulty Distribution',
								data: [$percentEasy, $percentMedium, $percentHard],
								backgroundColor: [
									'rgba(54, 162, 235, 0.6)',
									'rgba(255, 206, 86, 0.6)',
									'rgba(255, 99, 132, 0.6)'
								],
								borderColor: [
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(255, 99, 132, 1)'
								],
								borderWidth: 1
							}]
						},
						options: {
							responsive: false,
							maintainAspectRatio: false,
							plugins: {
								legend: {
									display: true,
									position: 'right'
								},
								title: {
									display: true,
									text: 'Difficulty Distribution'
								}
							}
						}
					});
				  </script>";
		}
	} else {
		echo "No tests found for the course.";
	}

	// Close connection
	$conn->close();
	?>
	</div>
</body>
</html>
