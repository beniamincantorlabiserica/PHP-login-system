<?php 
	require "functions.php";
	if(isset($_POST['submit'])){
		$response = registerUser($_POST['fullname'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm-password'], $_POST['phone'], $_POST['address']);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles.css">
	<script type="text/javascript" src="script.js"></script>
	<title>Register - Rotoy</title>
</head>
<body>
	<form action="" method="post" autocomplete="off" id="frm">
		<h2>Sign up</h2>
		<h4>
			Please fill this form to create an account. All fields are mandatory.<br>
		</h4>
		<p>
		<?php 
			if(@$response == "success"){
				?>
					<p class="success">Your registration was successful</p>
					<script>
						setTimeout('', 5000);
						window.location.href = "https://login.rotoy.dk/index.php"
					</script>
				<?php
				//header('Location: index.php');
			}else{
				?>
					<p class="error"><?php echo @$response; ?></p>
				<?php
			}
		?>
		</p>

		<div class="grid">
			<div>
				<label>Email</label>
				<input type="text" name="email" value="<?php echo @$_POST['email']; ?>" >
			</div>

			<div>
				<label>Full Name</label>
				<input type="text" name="fullname" value="<?php echo @$_POST['fullname']; ?>" >
			</div>

			<div>
				<label>Username</label>
				<input type="text" name="username" value="<?php echo @$_POST['username']; ?>" >
			</div>

			<div>
				<label>Password</label>
				<input type="password" name="password" value="<?php echo @$_POST['password']; ?>">
			</div>

			<div>
				<label>Confirm Password</label>
				<input type="password" name="confirm-password" value="<?php echo @$_POST['confirm-password']; ?>">
			</div>

			<div>
				<label>Address</label>
				<input type="text" name="address" value="<?php echo @$_POST['address']; ?>" >
			</div>

			<div>
				<label>Phone Number</label>
				<input type="text" name="phone" value="<?php echo @$_POST['phone']; ?>" >
			</div>
		</div>

		</div>	

		<button type="submit" name="submit">Submit</button>	

		<p>
			Already have an account? 
			<a href="index.php">Login here</a>
		</p>
	</form>
</body>
</html>
