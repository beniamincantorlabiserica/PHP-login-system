<?php 
	require "config.php";

	function saveValue($error){
		$error_date = date("F j, Y, g:i a");
		$message = "{$error} | {$error_date} \r\n";
		file_put_contents("log.txt", $message, FILE_APPEND);
	}


	function connect(){
		$mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
		if($mysqli->connect_errno != 0){
			$error = $mysqli->connect_error;
			$error_date = date("F j, Y, g:i a");
			$message = "{$error} | {$error_date} \r\n";
			file_put_contents("db-log.txt", $message, FILE_APPEND);
			return false;
		}else{
			$mysqli->set_charset("utf8mb4");
			return $mysqli;	
		}
	}

	function registerUser($fullname, $username, $email, $password, $confirm_password, $phone, $address){
		$mysqli = connect();
		$args = func_get_args();
		

		$args = array_map(function($value){
			return trim($value);
		}, $args);

		// foreach ($args as $value) {
		// 	if(empty($value)){
		// 		return "All fields are required";
		// 	}
		// }

		foreach ($args as $value) {
			if(preg_match("/([<|>])/", $value)){
				return "<> characters are not allowed";
			}
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			return "Email is not valid";
		}

		$stmt = $mysqli->prepare("SELECT email FROM users WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$data = $result->fetch_assoc();
		if($data != NULL){
			return "Email already exists, please use a different username";
		}

		if(strlen($fullname) > 100){
			return "Full name is to long";
		}

		if(strlen($username) > 100){
			return "Username is to long";
		}
		
		if(strlen($password) > 255){
			return "Password is to long";
		}

		if($password != $confirm_password){
			return "Passwords don't match";
		}

		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		 
		$r_date = date("Y-m-d");

		$mission = "1 1";
		$d = generateRole();
		$stmt = $mysqli->prepare("INSERT INTO users(username, fullname, email, password, phone, address, date) VALUES(?,?,?,?,?,?,?)");
		$stmt->bind_param("sssssss", $username, $fullname, $email, $hashed_password, $phone, $address, $r_date);
		$stmt->execute();
		if($stmt->affected_rows != 1){
			return "An error occurred. Please try again";
		}else{
		logDatabase($email, "Created new account");
			return "success";			
		}
	}

	function loginUser($username, $password){
		$mysqli = connect();
		$username = trim($username);
		$password = trim($password);
		
		if($username == "" || $password == ""){
			return "Both fields are required";
		}

		$username = filter_var($username, FILTER_SANITIZE_STRING);
		$password = filter_var($password, FILTER_SANITIZE_STRING);

		logDatabase($username, "Attempted logging in");

		$sql = "SELECT user_id, username, password, email, child_name d FROM users WHERE email = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$data = $result->fetch_assoc();


		if($data == NULL){
				return "Wrong email or password";
		}else{
			$email=$data["email"];
			$fullname = $data["username"];
			$user_id = $data["user_id"];
			$_SESSION['d']= $data["d"];
		}

		if(password_verify($password, $data["password"]) == FALSE){
			return "Wrong username or password";
		}else{
			logDatabase($email, "Logged in");
			$_SESSION["email"] = $email;
			$_SESSION["fullname"] = $fullname;
			$_SESSION["user_id"] = $user_id;
			header("location: account.php");
			exit();
		}
	}

	function logoutUser(){
		logDatabase($_SESSION['email'], "Logged out");
		session_destroy();
		header("location: index.php");
		exit();
	}

	function passwordReset($email){
		$mysqli = connect();
		$email = trim($email);

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			return "Email is not valid";
		}

		logDatabase($email, "Attempted password reset");

		$stmt = $mysqli->prepare("SELECT email FROM users WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$data = $result->fetch_assoc();

		if($data == NULL){
			return "Email doesn't exist in the database";
		}

		$str = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz";
		$password_length = 7;
		$new_pass = substr(str_shuffle($str), 0, $password_length);
		
		$hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);

		$stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE email = ?");
		$stmt->bind_param("ss", $hashed_password, $email);
		$stmt->execute();
		if($stmt->affected_rows != 1){
			return "There was a connection error, please try again."; 
		}

		$to = $email; 
		$subject = "Password recovery"; 
		$body = "You can log in with your new password". "\r\n";
		$body .= $new_pass; 

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: Admin \r\n";

		$send = mail($to, $subject, $body, $headers); 
		if(!$send){ 
			return "Email not send. Please try again";
		}else{
			logDatabase($email, "Reset password");
			return "success";
		}
	}

	function logDatabase($responsible, $action) {
		$mysqli = connect();

		$timestamp = date("Y-m-d h:i:s");

		$stmt = $mysqli->prepare("INSERT INTO log(action, responsible, timestamp) VALUES(?,?,?)");
		$stmt->bind_param("sss", $action, $responsible, $timestamp);
		$stmt->execute();
		if($stmt->affected_rows != 1){
			return "An error occurred. Please try again";
		}else{
			return "success";			
		}
	}
?>