<?php
include 'nav_bar.php';
if(isset($_SESSION["uid"])) {
	header("Location: index.php");
	exit();
}
?>
<link rel="stylesheet" href="css/login.css">
<?php
$error = NULL;
if (isset($_REQUEST['username'])) {
	require('connect_db.php');
	// removes backslashes
	$username = stripslashes($_REQUEST['username']);
	// escapes special characters in a string
	$username = $conn->real_escape_string($username);
	$email    = stripslashes($_REQUEST['email']);
	$email    = $conn->real_escape_string($email);
	$password = stripslashes($_REQUEST['password']);
	$password = $conn->real_escape_string($password);

	if (empty($username)) {
		$error = "Empty username.";
	} elseif (empty($password)) {
		$error = "Empty password.";
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error = "Not a valid email address.";
	} elseif (strlen($password) < 8 || !preg_match("/^[a-zA-Z0-9]+$/", $password)) {
		$error = "Password must be at least 8 characters long and contains only alphanumeric characters.";
	} else {
		$sql = "INSERT into users (username, password, email) VALUES ('$username', '" . md5($password) . "', '$email')";
		$result = $conn->query($sql);
		if ($result) {
			$conn->close();
			header("Location: login.php");
			exit();
		} else {
			$error = "Database error. Please try again.";	
		}
	}
	$conn->close();
}
?>
<form class="form login-container" action="" method="post">
	<h1 class="login-title">Register</h1>
	<input type="text" class="login-input" name="username" placeholder="Username" required value="<?php
		if (isset($username)) {
			echo $username;
		}
	?>"/>
	<input type="email" class="login-input" name="email" placeholder="Email Adress" required value="<?php
		if (isset($email)) {
			echo $email;
		}
	?>">
	<input type="password" class="login-input" name="password" placeholder="Password" required >
	<?php
	if ($error) {
		echo "<h5>" . $error . "</h5>";
	}
	?>
	<input type="submit" name="submit" value="Register" class="login-button">
	<p class="link">Already have an account? <a href="login.php">Login here</a></p>
</form>