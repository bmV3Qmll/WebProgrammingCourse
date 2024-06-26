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
if (isset($_POST['username'])) {
	require('connect_db.php');
	$username = stripslashes($_REQUEST['username']);
	$username = $conn->real_escape_string($username);
	$password = stripslashes($_REQUEST['password']);
	$password = $conn->real_escape_string($password);

	if (empty($username)) {
		$error = "Empty username.";
	} elseif (empty($password)) {
		$error = "Empty password.";
	} elseif (strlen($password) < 8) {
		$error = "Password must be at least 8 characters long.";
	} elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
		$error = "Password must contain only alphanumeric characters.";
	} else {
		$sql = "SELECT uid FROM users WHERE username='$username' AND password='" . md5($password) . "'";
		$result = $conn->query($sql);
		if ($result->num_rows == 1) {
			$_SESSION['uid'] = $result->fetch_assoc()['uid'];
			$conn->close();
			header("Location: index.php");
			exit();
		}
		$error = "Incorrect username / password.";
	}
	$conn->close();
}
?>
<form class="form login-container" method="post" name="login">
	<h1 class="login-title">Login</h1>
	<input type="text" class="login-input" name="username" placeholder="Username" autofocus="true" value="<?php
		if (isset($username)) {
			echo $username;
		}
	?>"/>
	<input type="password" class="login-input" name="password" placeholder="Password"/>
	<?php
	if ($error) {
		echo "<h5 class='error'>" . $error . "</h5>";
	}
	?>
	<input type="submit" value="Login" name="submit" class="login-button"/>
	<p class="link">Don't have an account? <a href="register.php">Register Now</a></p>
</form>