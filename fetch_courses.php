<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assignment";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Pagination
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * 6;

// Fetch courses
$sql = "SELECT * FROM courses LIMIT 6 OFFSET $offset";
$result = $conn->query($sql);

$courses_html = '';
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$name = $row['name'];
		$imagePath = $row['image_path'];

		$courses_html .= '<div class="col-md-4 course-card">';
		$courses_html .= '<img src="' . $imagePath . '" alt="' . $name . '">';
		$courses_html .= '<h3 class="course-name">' . $name . '</h3>';
		$courses_html .= '</div>';
	}
}

// Pagination links
$sql = "SELECT COUNT(*) AS total FROM courses";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalCourses = $row['total'];
$totalPages = ceil($totalCourses / 6);

$pagination_html = '';
for ($i = 1; $i <= $totalPages; $i++) {
	$pagination_html .= '<li class="page-item ' . ($page == $i ? 'active' : '') . '">';
	$pagination_html .= '<a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a>';
	$pagination_html .= '</li>';
}

// Close connection
$conn->close();

// Response data
$response = [
	'courses_html' => $courses_html,
	'pagination_html' => $pagination_html
];

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
