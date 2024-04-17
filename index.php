<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Course Display</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<!-- Custom CSS -->
	<style>
		.course-card {
			border: 1px solid #ccc; /* Add a border around each course */
			border-radius: 5px; /* Add some border radius for rounded corners */
			padding: 20px; /* Add padding inside the course box */
			text-align: center; /* Center the content horizontally */
			margin-bottom: 20px; /* Add some space between each course */
			cursor: pointer; /* Change cursor to pointer on hover */
		}

		.course-name {
			margin-top: 10px; /* Add some space between the image and the course name */
			font-weight: bold; /* Make the course name bold */
		}

		img {
		  width: 300px;
		  height: 200px;
		}
	</style>
</head>
<body>

<div class="container">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h1>Courses</h1>
		<a href="add_course.php" class="btn btn-primary">Add Course</a>
	</div>
	
	<div id="courseContainer" class="row">
		<!-- Courses will be displayed here using AJAX -->
	</div>

	<nav aria-label="Page navigation">
		<ul id="pagination" class="pagination justify-content-center">
			<!-- Pagination links will be added here -->
		</ul>
	</nav>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function(){
	// Function to fetch courses via AJAX
	function fetchCourses(page) {
		let xhttp = new XMLHttpRequest();
		let url = 'fetch_courses.php?page=' + page
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				let responseJSON = JSON.parse(this.responseText);
				$('#courseContainer').html(responseJSON.courses_html);
				$('#pagination').html(responseJSON.pagination_html);
			}
		};
		xhttp.open("GET", url, true);
		xhttp.send();
	}

	// Initial fetch for page 1
	fetchCourses(1);

	// Pagination click event
	$(document).on('click', '.page-link', function(e){
		e.preventDefault();
		var page = $(this).attr('data-page');
		fetchCourses(page);
	});

	// Redirect to course page when clicking on a course container
	$(document).on('click', '.course-card', function(){
		var courseName = $(this).find('.course-name').text();
		window.location.href = 'view_course.php?name=' + courseName;
	});
});
</script>

</body>
</html>
