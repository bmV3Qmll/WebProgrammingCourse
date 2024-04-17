<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>View Question</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="question.css">
</head>
<body>

<div class="container">
	<?php
	include 'connect_db.php';
	$questionId = $_GET['qid'] ?? 0;
	include 'get_question.php';
	// Close connection
	$conn->close();
	?>

	<script>
		document.getElementsByClassName('viewAnswerBtn')[0].addEventListener('click', function() {
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
	</script>
	<script type="text/javascript" src="question.js"></script>
</div>

</body>
</html>
