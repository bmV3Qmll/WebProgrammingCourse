<link rel="stylesheet" href="css/manage.css">
<?php
include 'nav_bar.php';
if(!isset($_SESSION["uid"])) {
	header("Location: login.php");
	exit();
}
include 'connect_db.php';
echo '<div class="container">';
$uid = $_SESSION["uid"];
$username = ($conn->query("SELECT username FROM users WHERE uid=$uid"))->fetch_assoc()['username'];
echo '<h1>User "' . $username . '"</h1>';

$sql = "SELECT tid, title FROM tests WHERE uid=$uid";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	echo '<h3 id="testPrompt">Contribute ' . $result->num_rows . ' tests</h3>';
	echo '<div id="testContainer">';
	while ($row = $result->fetch_assoc()) {
		echo '<div class="d-flex justify-content-between align-items-center mb-3 bounding-box">';
		echo '<a href="view_test.php?tid=' . $row['tid'] . '" target="_blank">' . $row['title'] . '</a>';
		echo '<button type="button" class="btn btn-outline-danger removeOptionBtn">Delete</button>';
		echo '</div>';
	}
	echo '</div>';
}

$sql = "SELECT cid, qid, title FROM questions WHERE uid=$uid";
$result = $conn->query($sql);
$mapping = [];
if ($result->num_rows > 0) {
	echo '<h3 id="questionPrompt">Contribute ' . $result->num_rows . ' questions</h3>';
	while ($row = $result->fetch_assoc()) {
		$cid = $row['cid'];
		$comp = [
			"qid" => $row['qid'],
			"title" => $row['title']
		];
		if (array_key_exists($cid, $mapping)) {
			$mapping[$cid][] = $comp;
		} else {
			$mapping[$cid] = [$comp];
		}
	}
	echo '<div id="questionContainer">';
	foreach ($mapping as $cid => $questions) {
		$courseName = ($conn->query("SELECT name FROM courses WHERE cid = $cid"))->fetch_assoc()['name'];
		echo '<div class="d-flex justify-content-between align-items-center mb-3 bounding-box">';
		echo '<div class="mr-auto p-2"><h4>' . $courseName . '</h4></div>';
		echo '<div class="p-2"><p>' . count($questions) . ' questions</p></div>';
		echo '<div class="p-2"><i class="fa fa-arrow-down" aria-hidden="true"></i></div>';
		echo '</div>';
		echo '<div class="dropdown">';
		foreach ($questions as $i => $comp) {
			echo '<div class="d-flex justify-content-start align-items-center">';
			echo '<i class="fa fa-times removeOptionBtn" style="color: red" aria-hidden="true"></i>';
			echo '<a href="view_question.php?qid=' . $comp['qid'] . '" target="_blank">' . $comp['title'] . '</a>';
			echo '</div>';
		}
		echo '</div>';
	}
	echo '</div>';
}
echo '</div>';
?>
<div id="customPrompt" class="overlay">
	<div class="prompt">
		<div id="promptText">Are you sure you want to delete this item?</div>
		<div class="buttons">
			<button id="yesButton">Yes</button>
			<button id="noButton">No</button>
		</div>
	</div>
</div>
<div id="messageBox" class="alert alert-info message-box" role="alert"></div>
<script>
	var customPrompt = document.getElementById("customPrompt");
	var yesButton = document.getElementById("yesButton");
	var noButton = document.getElementById("noButton");
	function showPrompt(callback) {
		customPrompt.style.display = "block";
		return new Promise(function(resolve, reject) {
			// Resolve or reject the promise based on user input
			yesButton.addEventListener("click", function() {
				hidePrompt();
				resolve(true); // Resolve with true if user clicked Yes
			});

			noButton.addEventListener("click", function() {
				hidePrompt();
				resolve(false); // Resolve with false if user clicked No
			});
		});
	}
	function hidePrompt() {
		customPrompt.style.display = "none";
	}

	var messageBox = document.getElementById('messageBox');

	var testContainer = document.getElementById("testContainer");
	if (testContainer !== null) {
		testContainer.addEventListener("click", function(event) {
			if (event.target.classList.contains("removeOptionBtn")) {
				var testDiv = event.target.previousElementSibling;
				showPrompt().then((stat) => {
					if (stat) {
						var xhttp = new XMLHttpRequest();
						var url = 'delete_item.php?' + testDiv.href.split('?')[1];
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								messageBox.textContent = this.responseText;
								var stat = 0;
								if (this.responseText == "Delete successfully.") {
									event.target.parentNode.remove();
									stat = 1;
									var testPrompt = document.getElementById("testPrompt");
									var str = testPrompt.textContent;
									var empty = false
									str = str.replace(/(\d+)/, function(match, n) {
										n = parseInt(n) - 1;
										if (n == 0) {
											empty = true;
										}
										return n;
									});
									if (empty) {
										testPrompt.remove();
									} else {
										testPrompt.textContent = str;
									}
								}
								showMessage(stat);
							}
						};
						xhttp.open("GET", url, true);
						xhttp.send();
					}
				});
			}
		});
	}

	var questionContainer = document.getElementById("questionContainer");
	if (questionContainer !== null) {
		questionContainer.addEventListener("click", function(event) {
			if (event.target.classList.contains("bounding-box")) {
				var dropdownDiv = event.target.nextElementSibling;
				var arrow = event.target.querySelector('.fa');
				dropdownDiv.classList.toggle("show");
				arrow.classList.toggle("arrow");
			}
		});
		questionContainer.addEventListener("click", function(event) {
			if (event.target.classList.contains("removeOptionBtn")) {
				var questionDiv = event.target.nextElementSibling;
				showPrompt().then((stat) => {
					if (stat) {
						var xhttp = new XMLHttpRequest();
						var url = 'delete_item.php?' + questionDiv.href.split('?')[1];
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								messageBox.textContent = this.responseText;
								var stat = 0;
								if (this.responseText == "Delete successfully.") {
									event.target.parentNode.remove();
									stat = 1;
									var questionPrompt = document.getElementById("questionPrompt");
									var str = questionPrompt.textContent;
									str = str.replace(/(\d+)/, function(match, n) {
										return parseInt(n) - 1;
									});
									questionPrompt.textContent = str;
								}
								showMessage(stat);
								refresh();
							}
						};
						xhttp.open("GET", url, true);
						xhttp.send();
					}
				});
			}
		});
	}

	function refresh() {
		var childDivs = Array.from(questionContainer.children);
		for (let i = 0; i < childDivs.length; i += 2) {
			var info = childDivs[i];
			var dropdownDiv = childDivs[i + 1];
			var noQuestions = dropdownDiv.children.length;
			if (noQuestions == 0) {
				info.remove();
				dropdownDiv.remove();
			} else {
				info.querySelector("p").textContent = noQuestions + ' questions';
			}
		}
	}
</script>