<?php include 'nav_bar.php' ?>
<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<link rel="stylesheet" href="css/test.css">

<div class="container">
<?php
include 'connect_db.php';
$courseName = $_GET['name'];
$sql = "SELECT cid FROM courses WHERE name = '$courseName'";

$result = $conn->query($sql);

$cid = $result->fetch_assoc()['cid'];

echo '<div class="d-flex justify-content-between align-items-center mb-3">';
echo '<div class="mr-auto p-2"><h1>' . $courseName . '</h1></div>';
if(isset($_SESSION["uid"])) {
	echo '<div class="p-2"><a href="add_test.php?cid=' . $cid . '" class="btn btn-primary">Add Test</a></div>';
	echo '<div class="p-2"><a href="add_question.php?cid=' . $cid . '" class="btn btn-primary">Add Question</a></div>';
}
echo '</div>';

// Retrieve tests for the course
$sql = "SELECT tid, title, no_easy, no_medium, no_hard FROM tests WHERE cid = '$cid'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$tid = $row['tid'];
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

		// echo '<div class="test-card">';
		echo '<div class="d-flex justify-content-between align-items-center mb-3 bounding-box">';
		echo '<div class="mr-auto p-2"><a href="view_test.php?tid=' . $tid . '">' . $title . '</a></div>';
		echo '<div class="p-2"><h5>' . $totalQuestions . ' questions</h5></div>';
		echo '<div class="p-2"><canvas id="chart_' . $title . '" width="200" height="80"></canvas></div>';
		echo '</div>';

		// JavaScript to create pie chart
		$escaped_title = str_replace("'", "\'", $title);
		echo "<script>
				var ctx = document.getElementById('chart_$escaped_title').getContext('2d');
				var myChart = new Chart(ctx, {
					type: 'pie',
					data: {
						labels: ['Easy', 'Medium', 'Hard'],
						datasets: [{
							label: 'Difficulty Distribution',
							data: [$percentEasy, $percentMedium, $percentHard],
							backgroundColor: [
								'rgba(51, 255, 51, 0.6)',
								'rgba(255, 206, 86, 0.6)',
								'rgba(255, 99, 132, 0.6)'
							],
							borderColor: [
								'rgba(51, 255, 51, 1)',
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
								display: false,
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