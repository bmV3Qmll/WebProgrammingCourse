<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Test Suite</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
		.navbar-brand {
			font-size: 2rem;
		}
		.navbar {
			display: flex;
			justify-content: space-between;
			align-items: center;
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
				<!--
				<li class="nav-item">
					<a class="nav-link" href="index.php"><i class="fa fa-home"></i> Home</a>
				</li>
				-->
				<?php
					session_start();
					if(!isset($_SESSION["username"])) {
						echo '<li class="nav-item">';
						echo '<a class="nav-link" href="login.php"><i class="fa fa-sign-in"></i> Login</a>';
						echo '</li>';
						echo '<li class="nav-item">';
						echo '<a class="nav-link" href="register.php"><i class="fa fa-registered"></i> Register</a>';
						echo '</li>';
					} else {
						echo '<li class="nav-item">';
						echo '<a class="nav-link" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>';
						echo '</li>';
					}
				?>
			</ul>
		</div>
	</nav>
</body>
</html>
