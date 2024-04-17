<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>View Question</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<!-- Custom CSS -->
	<style>
		.question {
			border: 1px solid #ccc;
			border-radius: 5px;
			padding: 20px;
			margin-bottom: 20px;
			position: relative;
		}
		.image {
			max-width: 100%;
			height: auto;
		}
		.difficulty {
			position: absolute;
			top: 10px;
			right: 10px;
			width: 30px;
			height: 30px;
			border-radius: 50%;
			text-align: center;
			line-height: 30px;
			font-weight: bold;
		}
		.difficulty.green {
			background-color: green;
			color: white;
		}
		.difficulty.yellow {
			background-color: yellow;
			color: black;
		}
		.difficulty.red {
			background-color: red;
			color: white;
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

	// Retrieve question ID from URL parameter
	$questionId = $_GET['qid'] ?? 0;

	// Retrieve question details from the database
	$sql = "SELECT title, description, options, difficulty, image_path, multiple FROM questions WHERE qid = $questionId";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// Output data of each row
		$row = $result->fetch_assoc();
		$title = $row["title"];
		$description = $row["description"];
		$options = json_decode($row["options"], true);
		$difficulty = $row["difficulty"];
		$imagePath = $row["image_path"];
		$multiple = $row["multiple"];

		// Determine the color class based on difficulty
		$difficultyClass = '';
		switch ($difficulty) {
			case 'easy':
				$difficultyClass = 'green';
				break;
			case 'medium':
				$difficultyClass = 'yellow';
				break;
			case 'hard':
				$difficultyClass = 'red';
				break;
			default:
				$difficultyClass = 'green';
		}

		echo '<div class="question">';
		echo '<div class="difficulty ' . $difficultyClass . '">' . strtoupper(substr($difficulty, 0, 1)) . '</div>';
		echo '<h2>' . $title . '</h2>';
		echo '<p>' . $description . '</p>';

		// Display image if available
		if (!empty($imagePath)) {
			echo '<img src="' . $imagePath . '" alt="Question Image" class="image">';
		}

		// Display options with checkboxes
		echo '<form id="answerForm">';
		foreach ($options as $text => $result) {
			echo '<div class="form-check">';
			if ($multiple == 1) {
				echo '<input class="form-check-input" type="checkbox" name="' . $questionId . '" value="' . $result . '">';
			} else {
				echo '<input class="form-check-input" type="checkbox" name="' . $questionId . '" value="' . $result . '" onclick="onlyOne(this)">';
			}
			echo '<label class="form-check-label">' . $text . '</label>';
			echo '</div>';
		}
		echo '</form>';

		// Button to view/hide answer
		echo '<button id="viewAnswerBtn" class="btn btn-primary mt-3">View Answer</button>';

		echo '</div>';
	} else {
		echo "Question not found.";
	}

	// Close connection
	$conn->close();
	?>

	<script>
		document.getElementById('viewAnswerBtn').addEventListener('click', function() {
			var checkboxes = document.querySelectorAll('input[type="checkbox"]');
			if (this.textContent === 'View Answer') {
				checkboxes.forEach(function(checkbox) {
					checkbox.disabled = true;
					if (checkbox.value === "1") {
						checkbox.checked = true;
					} else {
						checkbox.checked = false;
					}
				});
				this.textContent = 'Hide Answer';
			} else {
				checkboxes.forEach(function(checkbox) {
					checkbox.checked = false;
					checkbox.disabled = false;
				});
				this.textContent = 'View Answer';
			}
		});

		function onlyOne(checkbox) {
			var checkboxes = document.getElementsByName(checkbox.name)
			checkboxes.forEach((item) => {
				if (item !== checkbox) item.checked = false
			})
		}
	</script>

</div>

</body>
</html>
