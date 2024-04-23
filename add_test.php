<?php
include 'nav_bar.php';
if(!isset($_SESSION["uid"])) {
	header("Location: login.php");
	exit();
}
?>
<style>
	.warning {
		color: red;
		font-weight: bold;
	}
</style>
<div class="container">
	<?php
	include 'connect_db.php';
	$cid = $_GET['cid'];
	$sql = "SELECT name FROM courses WHERE cid = $cid";
	$result = $conn->query($sql);
	$courseName = $result->fetch_assoc()['name'];
	echo '<div class="d-flex justify-content-between align-items-center mt-3">';
	echo '<h1>' . $courseName . '</h1>';
	echo '<a href="view_course.php?name=' . $courseName . '" class="btn btn-primary">Back to Course</a>';
	echo '</div>';
	
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['cid'])) {
		$stat = 0;
		echo '<div id="messageBox" class="alert alert-info message-box" role="alert">';
		$title = $conn->real_escape_string($_POST['title']);
		$description = $conn->real_escape_string($_POST['description']);
		$no_easy = 0;
		if (isset($_POST['easy'])) {
			$no_easy = count($_POST['easy']);
		}
		$no_medium = 0;
		if (isset($_POST['medium'])) {
			$no_medium = count($_POST['medium']);
		}
		$no_hard = 0;
		if (isset($_POST['hard'])) {
			$no_hard = count($_POST['hard']);
		}

		if ($no_easy + $no_medium + $no_hard > 0) {
			$uid = $_SESSION["uid"];
			$sql = "INSERT INTO tests (title, description, cid, no_easy, no_medium, no_hard, uid) VALUES ('$title', '$description', '$cid', $no_easy, $no_medium, $no_hard, $uid)";
			try {
				$conn->query($sql);

				// Get the ID of the inserted test
				$tid = $conn->insert_id;

				if ($no_easy > 0) {
					foreach ($_POST['easy'] as $qid) {
						$sql = "INSERT INTO test_question (tid, qid) VALUES ('$tid', '$qid')";
						$conn->query($sql);
					}
				}
				if ($no_medium > 0)  {
					foreach ($_POST['medium'] as $qid) {
						$sql = "INSERT INTO test_question (tid, qid) VALUES ('$tid', '$qid')";
						$conn->query($sql);
					}
				}
				if ($no_hard > 0)  {
					foreach ($_POST['hard'] as $qid) {
						$sql = "INSERT INTO test_question (tid, qid) VALUES ('$tid', '$qid')";
						$conn->query($sql);
					}
				}
				echo "Test added successfully. <br>";
				$stat = 1;
			} catch (mysqli_sql_exception $e) {
				echo "Error: " . mysqli_error($conn) . " <br>";
			}
		} else {
			echo "The test must have at least one question.";
		}
		echo '</div>';
		echo '<script>showMessage(' . $stat . ')</script>';
	} 

	$easyQuestions = [];
	$mediumQuestions = [];
	$hardQuestions = [];

	$sql = "SELECT qid, title, difficulty FROM questions WHERE cid = $cid ORDER BY difficulty";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// Group questions by difficulty level
		while ($row = $result->fetch_assoc()) {
			$qid = $row["qid"];
			$title = $row["title"];
			$difficulty = $row["difficulty"];

			switch ($difficulty) {
				case 'easy':
					$easyQuestions[] = ['qid' => $qid, 'title' => $title];
					break;
				case 'medium':
					$mediumQuestions[] = ['qid' => $qid, 'title' => $title];
					break;
				case 'hard':
					$hardQuestions[] = ['qid' => $qid, 'title' => $title];
					break;
			}
		}
	}

	$conn->close();
	

	function displayQuestions($questions) {
		foreach ($questions as $question) {
			echo '<option value="' . $question["qid"] . '" ondblclick="viewQuestion(this)">' . $question["title"] . '</option>';
		}
	}
	?>
	<form action="" method="POST">
		<div class="form-group">
			<label for="title">Test Title:</label>
			<input type="text" class="form-control" id="title" name="title" required>
		</div>
		<div class="form-group">
			<label for="description">Description:</label>
			<textarea class="form-control" id="description" name="description" rows="3"></textarea>
		</div>
		<div class="form-group">
			<label for="num_easy">Number of Easy Questions:</label>
			<input type="number" class="form-control" id="num_easy" name="num_easy" min="0">
		</div>
		<div class="form-group">
			<label for="num_medium">Number of Medium Questions:</label>
			<input type="number" class="form-control" id="num_medium" name="num_medium" min="0">
		</div>
		<div class="form-group">
			<label for="num_hard">Number of Hard Questions:</label>
			<input type="number" class="form-control" id="num_hard" name="num_hard" min="0">
		</div>
		<button type="button" class="btn btn-primary" onclick="autoSelectQuestions()">Auto Select Questions</button>
		<div id="warning-msg" class="warning" style="display: none;"></div>
		<h5>Hold Crtl and click to select or unselect a question.</h5>
		<div class="form-group">
			<div class="d-flex justify-content-between align-items-center mb-0">
				<label for="easy">Select Easy Questions</label>
				<span id="easy-counter" class="counter">0</span>
			</div>
			<select class="form-control" id="easy" name="easy[]" multiple onchange="updateCounter('easy')" size="4">
				<!-- Populate options with easy questions -->
				<?php displayQuestions($easyQuestions); ?>
			</select>
		</div>
		<div class="form-group">
			<div class="d-flex justify-content-between align-items-center mb-0">
				<label for="medium">Select Medium Questions</label>
				<span id="medium-counter" class="counter">0</span>
			</div>
			<select class="form-control" id="medium" name="medium[]" multiple onchange="updateCounter('medium')" size="4">
				<!-- Populate options with medium questions -->
				<?php displayQuestions($mediumQuestions); ?>
			</select>
		</div>
		<div class="form-group">
			<div class="d-flex justify-content-between align-items-center mb-0">
				<label for="hard">Select Hard Questions</label>
				<span id="hard-counter" class="counter">0</span>
			</div>
			<select class="form-control" id="hard" name="hard[]" multiple onchange="updateCounter('hard')" size="4">
				<!-- Populate options with hard questions -->
				<?php displayQuestions($hardQuestions); ?>
			</select>
		</div>
		<button type="submit" class="btn btn-primary">Create Test</button>
	</form>
	<script>
		function viewQuestion(option) {
			// Open view_question.php in a new window with the question ID as parameter
			window.open('view_question.php?qid=' + option.getAttribute('value'), '_blank');
		}
		function updateCounter(selectId) {
			var selectElement = document.getElementById(selectId);
			var counterElement = document.getElementById(selectId + '-counter');
			var count = 0;

			// Count the number of selected options
			for (var i = 0; i < selectElement.options.length; i++) {
				if (selectElement.options[i].selected) {
					count++;
				}
			}

			// Update the counter text
			counterElement.textContent = count;
		}
		const totalEasy = document.getElementById('easy').options.length;
		const totalMedium = document.getElementById('medium').options.length;
		const totalHard = document.getElementById('hard').options.length;

		document.getElementById('num_easy').setAttribute("max", totalEasy);
		document.getElementById('num_medium').setAttribute("max", totalMedium);
		document.getElementById('num_hard').setAttribute("max", totalHard);

		function autoSelectQuestions() {
			warningMsg = document.getElementById('warning-msg');
			var numEasy = parseInt(document.getElementById('num_easy').value) || 0;
			var numMedium = parseInt(document.getElementById('num_medium').value) || 0;
			var numHard = parseInt(document.getElementById('num_hard').value) || 0;

			warningMsg.style.display = 'block';
			if (numEasy > totalEasy) {
				warningMsg.textContent = 'Not enough easy questions. Maximum available: ' + totalEasy;
			} else if (numMedium > totalMedium) {
				warningMsg.textContent = 'Not enough medium questions. Maximum available: ' + totalMedium;
			} else if (numHard > totalHard) {
				warningMsg.textContent = 'Not enough hard questions. Maximum available: ' + totalHard;
			} else {
				warningMsg.style.display = 'none';
				var selectedEasy = document.querySelectorAll('#easy option:checked').length;
				var selectedMedium = document.querySelectorAll('#medium option:checked').length;
				var selectedHard = document.querySelectorAll('#hard option:checked').length;

				randomSelect('easy', numEasy - selectedEasy);
				randomSelect('medium', numMedium - selectedMedium);
				randomSelect('hard', numHard - selectedHard);
			}
		}
		const shuffle = ([...arr]) => {
			let m = arr.length;
			while (m) {
				const i = Math.floor(Math.random() * m--);
				[arr[m], arr[i]] = [arr[i], arr[m]];
			}
			return arr;
		};
		const sampleSize = ([...arr], n = 1) => shuffle(arr).slice(0, n);
		function randomSelect(selectId, remain) {
			var selectElement = document.getElementById(selectId);
			var counterElement = document.getElementById(selectId + '-counter');
			var checkedOptions = [];
			var uncheckedOptions = [];
			for (var i = 0; i < selectElement.options.length; i++) {
				var option = selectElement.options[i];
				if (option.selected) {
					checkedOptions.push(option);
				} else {
					uncheckedOptions.push(option);
				}
			}
			
			if (remain < 0) {
				for (var opt of sampleSize(checkedOptions, -remain)) {
					opt.selected = false
				}
			} else if (remain > 0) {
				for (var opt of sampleSize(uncheckedOptions, remain)) {
					opt.selected = true
				}
			}
			counterElement.textContent = parseInt(counterElement.textContent) + remain;
		}
	</script>
</div>