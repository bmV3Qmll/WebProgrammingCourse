<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-check-input[type="checkbox"] {
            width: 2rem; /* Adjust width of checkbox */
            height: 2rem; /* Adjust height of checkbox */
            margin-left: -0.85rem;
        }

        /* Adjust position of checkbox */
        .input-group-prepend .input-group-text {
            width: 2rem; /* Adjust width to match checkbox */
            padding-right: 0rem; /* Add space between checkbox and text input */
        }
    </style>
</head>
<body>
<div class="container">
<?php
// Check if CID is provided in URL
if (!isset($_GET['cid'])) {
    echo "<p>Error: Course ID (CID) is missing.</p>";
    exit;
}

$cid = $_GET['cid'];

// Include database connection
include 'connect_db.php';
$sql = "SELECT name FROM courses WHERE cid = $cid";
$result = $conn->query($sql);
$courseName = $result->fetch_assoc()['name'];
echo '<h1 class="mt-3">' . $courseName . '</h1>';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imagePath = NULL;
    include 'upload_image.php';
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $difficulty = strtolower($_POST['difficulty']);
    $multiple = 0;

    $options = [];
    if (isset($_POST['options']) && is_array($_POST['options']) && isset($_POST['optionContent']) && is_array($_POST['optionContent'])) {
        $optionValues = $_POST['options'];
        $optionContents = $_POST['optionContent'];
        foreach ($optionContents as $index => $content) {
            $options[$content] = in_array($index, $optionValues) ? "1" : "0";
        }
        if (count($optionValues) != 1) {
            $multiple = 1;
        }
    }
    $options = json_encode($options);

    if ($imageOk == 1) {
        $sql = "";
        if ($imagePath) {
            $sql = "INSERT INTO questions (title, description, cid, options, difficulty, image_path, multiple) 
            VALUES ('$title', '$description', '$cid', '$options', '$difficulty', '$imagePath', '$multiple')";
        } else {
            $sql = "INSERT INTO questions (title, description, cid, options, difficulty, multiple) 
            VALUES ('$title', '$description', '$cid', '$options', '$difficulty', '$multiple')";
        }
        
        try {
            $conn->query($sql);
            echo "<p>Question added successfully.</p>";
        } catch (mysqli_sql_exception $e) {
            echo "<p>Error: " . mysqli_error($conn) . "</p>";
        }
    }

    // Close database connection
    mysqli_close($conn);
}
?>
    <form id="uploadForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?cid=' . $cid; ?>" class="mt-4" enctype="multipart/form-data">
        <input type="hidden" name="cid" value="<?php echo $cid; ?>">
        <div class="form-group">
            <label for="title">Question Title:</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="image">Upload Image:</label>
            <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
        </div>
        <h5>Check the option to mark correct answer(s).</h5>
        <div id="optionsContainer">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" class="form-check-input" name="options[]" value="1">
                        </div>
                    </div>
                    <input type="text" class="form-control" name="optionContent[]" placeholder="Option 1" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-danger removeOptionBtn">Remove</button>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="addOptionBtn">Add Option</button>
        <div class="form-group">
            <label for="difficulty">Difficulty:</label>
            <select id="difficulty" name="difficulty" class="form-control" required>
                <option value="Easy">Easy</option>
                <option value="Medium">Medium</option>
                <option value="Hard">Hard</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" onclick="onBtnClick()">Submit</button>
    </form>
</div>
<script>
    var optionCount = 1;
    var addOptionBtn = document.getElementById("addOptionBtn");
    var optionsContainer = document.getElementById("optionsContainer");

    addOptionBtn.addEventListener("click", function() {
        optionCount++;
        var optionHtml = `
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" class="form-check-input" name="options[]" value="${optionCount}">
                        </div>
                    </div>
                    <input type="text" class="form-control" name="optionContent[]" placeholder="Option ${optionCount}" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-danger removeOptionBtn">Remove</button>
                    </div>
                </div>
            </div>
        `;
        optionsContainer.insertAdjacentHTML('beforeend', optionHtml);
    });
    optionsContainer.addEventListener("click", function(event) {
        if (event.target.classList.contains("removeOptionBtn")) {
            var optionDiv = event.target.closest(".form-group");
            optionDiv.remove();
        }
    });
    function onBtnClick() {
        var opts = document.getElementsByName('options[]');
        for (var i = 0; i < opts.length; i++) {
            opts[i].value = i.toString();
        }
        document.getElementById("uploadForm").submit();
    }
</script>

</body>
</html>
