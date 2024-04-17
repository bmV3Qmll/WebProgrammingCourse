<?php

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
	echo '<h4>' . $title . '</h4>';
	echo '<p>' . nl2br($description) . '</p>';

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
		echo '<label class="form-check-label">' . htmlspecialchars($text) . '</label>';
		echo '<label class="checkbox-label"></label>';
		echo '</div>';
	}
	echo '</form>';
	// Button to view/hide answer
	echo '<button class="btn btn-primary mt-3 viewAnswerBtn">View Answer</button>';
	echo '</div>';
} else {
	echo "Question not found.";
}
?>

