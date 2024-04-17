<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Test</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="question.css">
</head>
<body>
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
            echo '<h1>' . $test['title'] . '</h1>';
            echo '<p>' . $test['description'] . '</p>';

            // Fetch questions associated with the test from the database
            $sql = "SELECT qid FROM test_question WHERE tid = '$tid'";
            $questions = $conn->query($sql);

            // Loop through each question and include view_question.php
            while ($row = $questions->fetch_assoc()) {
                $questionId = $row['qid'];
                include 'get_question.php';
            }
        } else {
            echo "Test not found.";
        }
    } else {
        echo "Test ID (tid) parameter is missing.";
    }
    ?>
    <button id="submitBtn" class="btn btn-primary mt-3">Submit</button>
    </div>
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
            this.textContent = "Number of correct answer " + noCorrect;
            this.disabled = true;
        });
    </script>
    <script type="text/javascript" src="question.js"></script>
</body>
</html>
