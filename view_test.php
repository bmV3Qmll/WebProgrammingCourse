<?php include 'nav_bar.php' ?>
<link rel="stylesheet" href="question.css">

<div class="container">
<?php
include 'connect_db.php';

// Check if 'tid' parameter is present in the URL
if (isset($_GET['tid'])) {
	$tid = $_GET['tid'];

	// Fetch test details from the database
	$sql = "SELECT title, description FROM tests WHERE tid = '$tid'";
	$result = $conn->query($sql);

	// Check if test exists
	if ($result->num_rows > 0) {
		// Display test title and description
		$test = $result->fetch_assoc();
		echo '<div id="contentToExport">';
		echo '<div class="d-flex justify-content-between align-items-center mb-3">';
		echo '<h1>' . $test['title'] . '</h1>';
		echo '<button id="exportButton" class="btn btn-primary">Export to DOCX</button>';
		echo '</div>';
		echo '<p>' . $test['description'] . '</p>';

		// Fetch questions associated with the test from the database
		$sql = "SELECT qid FROM test_question WHERE tid = '$tid' ORDER BY RAND()";
		$questions = $conn->query($sql);

		// Loop through each question and include view_question.php
		while ($row = $questions->fetch_assoc()) {
			$questionId = $row['qid'];
			include 'get_question.php';
		}
		echo '</div>';
	} else {
		echo "Test not found.";
	}
} else {
	echo "Test ID (tid) parameter is missing.";
}
?>
<button id="submitBtn" class="btn btn-primary mt-3">Submit</button>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.4/mammoth.browser.min.js"></script>
<script>
	var btns = Array.from(document.getElementsByClassName('viewAnswerBtn'));
	btns.forEach(function(btn) {
		btn.parentNode.removeChild(btn);
	});
	document.getElementById('submitBtn').addEventListener('click', function() {
		var checkboxes = document.querySelectorAll('input[type="checkbox"]');
		var optionsOf = {};
		var noCorrect = 0;
		checkboxes.forEach(function(checkbox) {
			checkbox.disabled = true;
			var name = checkbox.getAttribute('name');
			if (!optionsOf[name]) {
				optionsOf[name] = [];
			}
			optionsOf[name].push(checkbox);
			
		});

		Object.entries(optionsOf).forEach(function([qid, opts]) {
			var allCorrect = true
			for (var opt of opts) {
				var correctness = opt.value === "1";
				if (opt.checked != correctness) {
					allCorrect = false
				}

				var thisLabel = opt.nextElementSibling.nextElementSibling;
				if (opt.checked && correctness) {
					thisLabel.textContent = "✓";
					thisLabel.style.color = "green";
				}
				if (opt.checked && !correctness) {
					thisLabel.textContent = "✗";
					thisLabel.style.color = "red";
				}
			}
			if (allCorrect) {
				++noCorrect;
			}
		});

		checkboxes.forEach(function(checkbox) {
			checkbox.disabled = true;
			if (checkbox.value === "1") {
				checkbox.checked = true;
			} else {
				checkbox.checked = false;
			}
		});
		this.textContent = "Number of correct answers: " + noCorrect;
		this.disabled = true;
	});
	document.getElementById('exportButton').addEventListener('click', function() {
		Export2Word('contentToExport', 'x');
	});
	function Export2Word(element, filename = ''){
		var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>TestSuite</title></head><body>";
		var postHtml = "</body></html>";
		var html = preHtml + document.getElementById(element).innerHTML + postHtml;

		var blob = new Blob(['\ufeff', html], {
			type: 'application/msword'
		});
		
		// Specify link url
		var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);
		
		// Specify file name
		filename = filename?filename+'.doc':'document.doc';
		
		// Create download link element
		var downloadLink = document.createElement("a");

		document.body.appendChild(downloadLink);
		
		if(navigator.msSaveOrOpenBlob ){
			navigator.msSaveOrOpenBlob(blob, filename);
		} else {
			// Create a link to the file
			downloadLink.href = url;
			
			// Setting the file name
			downloadLink.download = filename;
			
			//triggering the function
			downloadLink.click();
		}
		
		document.body.removeChild(downloadLink);
	}
</script>
<script type="text/javascript" src="question.js"></script>