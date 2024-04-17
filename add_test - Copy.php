<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add test</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        .question-box {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 20px;
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

    // Retrieve course ID from URL parameter
    $courseId = $_GET['cid'];

    $sql = "SELECT name FROM courses WHERE cid = $courseId";
    $result = $conn->query($sql);
    $courseName = $result->fetch_assoc()['name'];

    echo '<div class="d-flex justify-content-between align-items-center mb-3">';
    echo '<h1>' . $courseName . '</h1>';
    echo '</div>';

    // Retrieve questions belonging to the course from the database, grouped by difficulty level
    $sql = "SELECT qid, title, difficulty FROM questions WHERE cid = $courseId ORDER BY difficulty";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Initialize arrays to hold questions grouped by difficulty level
        $easyQuestions = [];
        $mediumQuestions = [];
        $hardQuestions = [];

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

        // Function to display questions in a box
        function displayQuestions($questions) {
            echo '<div class="question-box">';
            foreach ($questions as $question) {
                echo '<div>';
                echo '<input type="checkbox" name="question[]" value="' . $question['qid'] . '"> ' . $question['title'];
                echo '<button onclick="viewQuestion(' . $question['qid'] . ')" class="btn btn-primary btn-sm ml-2">View</button>';
                echo '</div>';
            }
            echo '</div>';
        }

        // Display questions grouped by difficulty level
        if (!empty($easyQuestions)) {
            echo '<h3>Easy</h3>';
            displayQuestions($easyQuestions);
        }

        if (!empty($mediumQuestions)) {
            echo '<h3>Medium</h3>';
            displayQuestions($mediumQuestions);
        }

        if (!empty($hardQuestions)) {
            echo '<h3>Hard</h3>';
            displayQuestions($hardQuestions);
        }

    } else {
        echo "No questions found for this course.";
    }

    // Close connection
    $conn->close();
    ?>

    <script>
        function viewQuestion(qid) {
            // Open view_question.php in a new window with the question ID as parameter
            window.open('view_question.php?qid=' + qid, '_blank');
        }
    </script>
</div>

</body>
</html>
