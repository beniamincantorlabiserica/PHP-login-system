<?php 
	require "functions.php";

	if(isset($_POST['submit'])){
		$response = loginUser($_POST['username'], $_POST['password']);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles.css">
	<script type="text/javascript" src="script.js"></script>
	<title>Log In - Rotoy</title>
</head>
<body>
	<form action="" method="post" autocomplete="off">
		<h2>Sign in</h2>
		<h4>Please fil this form to sign in to your account.</h4>
		
		<div class="grid">
			<div>
				<label>Email</label>
				<input type="text" name="username" value="<?php echo @$_POST['username']; ?>">
			</div>

			<div>
				<label>Password</label>
				<input type="password" name="password" value="<?php echo @$_POST['password']; ?>">
			</div>
		</div>
		
		<button type="submit" name="submit">Sign in</button>	

		<p>
			Don't have an account? 
			<a href="register.php">Create here</a>
		</p>

		<p>
			<a href="forgot-password.php">Forgot password?</a>
		</p>

		<p class="error"><?php echo @$response; ?></p>

		<?php
			if(isset($_GET['ms'])) {
	        echo "<p class='error'>You have been logged out for unactivity</p>";
			}
		?>
	</form>
</body>
</html>
