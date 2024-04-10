<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RGB Question</title>
<style>
	body {
		font-family: Arial, sans-serif;
	}
	.container {
		max-width: 600px;
		margin: 0 auto;
		padding: 20px;
	}
	.question {
		font-weight: bold;
		margin-bottom: 10px;
	}
	.options {
		list-style-type: none;
		padding: 0;
	}
	.option {
		margin-bottom: 5px;
	}
</style>
</head>
<body>
<div class="container">
	<?php
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
	$id = $_GET['id'];
	$sql = "SELECT title, description, options, difficulty, image_path FROM questions WHERE qid = '$id')";

	$result = $conn->query($sql);
	?>
	<h2 class="question" id="questionTitle">Loading...</h2>
	<p id="questionDescription">Loading...</p>
	<ul class="options" id="optionsList">
		<!-- Options will be appended here -->
	</ul>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		// JSON data representing the question, description, and options
		const jsonData = {"question": "Which colors make up RGB?", "description": "RGB is ...", "options": {"Red":"1", "Blue":"1", "Green":"1", "Yellow":"0"}};

		// Function to create and append an option to the options list
		function addOption(label, value) {
			const optionsList = document.getElementById("optionsList");
			const optionItem = document.createElement("li");
			optionItem.classList.add("option");
			optionItem.innerHTML = `<label><input type="checkbox" value="${value}">${label}</label>`;
			optionsList.appendChild(optionItem);
		}

		// Display question title and description
		document.getElementById("questionTitle").textContent = jsonData.question;
		document.getElementById("questionDescription").textContent = jsonData.description;

		// Display options based on JSON data
		for (const key in jsonData.options) {
			if (jsonData.options.hasOwnProperty(key)) {
				addOption(key, jsonData.options[key]);
			}
		}
	});
</script>
</body>
</html>
