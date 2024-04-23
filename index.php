<?php include 'nav_bar.php' ?>
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
<div class="container">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h1>Courses</h1>
		<?php
			if(isset($_SESSION["uid"])) {
				echo '<a href="add_course.php" class="btn btn-primary">Add Course</a>';
			}
		?>
		
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
<script>
document.addEventListener("DOMContentLoaded", function() {
	// Function to fetch courses via AJAX
	function fetchCourses(page) {
		var xhttp = new XMLHttpRequest();
		var url = 'fetch_courses.php?page=' + page;
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				let responseJSON = JSON.parse(this.responseText);
				document.getElementById('courseContainer').innerHTML = responseJSON.courses_html;
				document.getElementById('pagination').innerHTML = responseJSON.pagination_html;
			}
		};
		xhttp.open("GET", url, true);
		xhttp.send();
	}

	// Initial fetch for page 1
	fetchCourses(1);

	// Pagination click event
	document.addEventListener('click', function(event) {
		if (event.target.classList.contains('page-link')) {
			event.preventDefault();
			var page = event.target.getAttribute('data-page');
			fetchCourses(page);
		}
	});

	var courseContainer = document.getElementById('courseContainer');
	// Redirect to course page when clicking on a course container
	courseContainer.addEventListener('click', function(event) {
		var card = event.target.closest('.course-card');
		if (card) {
			var courseName = card.querySelector('.course-name').textContent;
			window.location.href = 'view_course.php?name=' + encodeURIComponent(courseName);
		}
	});
});
</script>