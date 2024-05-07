<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?php
			$currentPage = substr($_SERVER['REQUEST_URI'], 1, -4);
			if (strlen($currentPage) !== 0 and $currentPage !== "index") {
				echo "Test Suite: " . $currentPage;
			}
			else {
				echo "Test Suite";
			}
		?>
	</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		.navbar-brand {
			font-size: 2rem;
		}
		.navbar {
			display: flex;
			justify-content: space-between;
			align-items: center;
		}
		.message-box {
			position: fixed;
			top: 80px;
			right: 20px;
			z-index: 9999;
			display: none;
			font-weight: bold;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="index.php">Test Suite</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse justify-content-end" id="navbarNav">
			<ul class="navbar-nav">
				<?php
					session_start();
					if(!isset($_SESSION["uid"])) {
						echo '<li class="nav-item">';
						echo '<a class="nav-link" href="login.php"><i class="fa fa-sign-in"></i> Login</a>';
						echo '</li>';
						echo '<li class="nav-item">';
						echo '<a class="nav-link" href="register.php"><i class="fa fa-registered"></i> Register</a>';
						echo '</li>';
					} else {
						echo '<li class="nav-item">';
						echo '<a class="nav-link" href="manage.php"><i class="fa fa-user"></i> Manage</a>';
						echo '</li>';
						echo '<li class="nav-item">';
						echo '<a class="nav-link" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>';
						echo '</li>';
					}
				?>
			</ul>
		</div>
	</nav>
	<script>
		function showMessage(stat) {
			var messageBox = document.getElementById('messageBox');
			messageBox.style.display = 'block';
			if (stat === 0) {
				messageBox.style.backgroundColor = "#ff7a7a";
			} else {
				messageBox.style.backgroundColor = "#99ff88";
			}
			setTimeout(function() {
				messageBox.style.display = 'none';
			}, 5000);
		}
	</script>
</body>
</html>
