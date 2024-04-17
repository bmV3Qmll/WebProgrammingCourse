<?php
// print_r($_REQUEST);
include 'connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['cid'])) {
    $cid = $_GET['cid'];
    $title = $_POST['title'];
    $description = $_POST['description'];
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
    
    $sql = "INSERT INTO tests (title, description, cid, no_easy, no_medium, no_hard) VALUES ('$title', '$description', '$cid', $no_easy, $no_medium, $no_hard)";
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

    mysqli_close($conn);

    // Redirect user to a success page or back to add_test.php
    //header("Location: add_test.php?cid=" . $cid);
    //exit();
} else {
    // Handle case where form was not submitted via POST
    echo "Error: Form was not submitted.";
}
?>
