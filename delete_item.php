<?php
session_start();
if(!isset($_SESSION["uid"])) {
	header("Location: login.php");
	exit();
}

include 'connect_db.php';
header('Content-Type: text/plain');

if (isset($_GET['tid'])) {
	$tid = $_GET['tid'];
	try {
		$sql = "SELECT uid FROM tests WHERE tid=$tid";
		if ($_SESSION["uid"] == ($conn->query($sql))->fetch_assoc()['uid']) {
			$sql = "DELETE FROM test_question WHERE tid=$tid";
			$conn->query($sql);
			$sql = "DELETE FROM tests WHERE tid=$tid";
			$conn->query($sql);
			echo "Delete successfully.";
		} else {
			echo "No permission to delete this test.";
		}
	} catch (mysqli_sql_exception $e) {
		echo "Error: " . mysqli_error($conn);
	}
}

if (isset($_GET['qid'])) {
	$qid = $_GET['qid'];
	try {
		$sql = "SELECT uid FROM questions WHERE qid=$qid";
		if ($_SESSION["uid"] == ($conn->query($sql))->fetch_assoc()['uid']) {
			$sql = "SELECT tid FROM test_question WHERE qid=$qid";
			$result = $conn->query($sql);
			if ($result->num_rows == 0) {
				$sql = "SELECT image_path FROM questions WHERE qid=$qid";
				$image_path = './' . ($conn->query($sql))->fetch_assoc()['image_path'];
				if ($image_path == './' || (file_exists($image_path) && unlink($image_path))) {
					$sql = "DELETE FROM test_question WHERE qid=$qid";
					$conn->query($sql);
					$sql = "DELETE FROM questions WHERE qid=$qid";
					$conn->query($sql);
					echo "Delete successfully.";
				} else {
					echo "Unable to delete the attached image.";
				}
			} else {
				echo "The question is currently used in a test.";
			}
		} else {
			echo "No permission to delete this question.";
		}
	} catch (mysqli_sql_exception $e) {
		echo "Error: " . mysqli_error($conn);
	}
}
?>