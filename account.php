<?php 
	require "functions.php";
	
	if(isset($_SESSION['fullname'])){
		$d = $_SESSION['d'];
		if(password_verify("673264", $d)) {
			require("admin.php");
		} else {
			require("user.php");
		}
	}

	if(!isset($_SESSION['fullname'])){
		header("location: index.php?ms=lo");
		exit();
	}

	if(isset($_GET['logout'])){
		logoutUser();
	}
	
	if(isset($_GET['confirm-account-deletion'])){
		deleteAccount();
	}
?>

