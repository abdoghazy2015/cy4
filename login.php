<!DOCTYPE html>
<html>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="css/style.css" />

<?php
require('db.php');
session_start();
// If form submitted, insert values into the database.
if (isset($_POST['username'])) {


	$username = (int)$_POST['username'];
	$password = stripslashes($_REQUEST['password']);
	$password = $db->escapeString( $password);
	$query = "SELECT * FROM `users` WHERE id='$username' and password='" . md5($password) . "'";
	$rows = $db->querySingle($query);

	if ($rows > 0) {
		$_SESSION['username'] = $username;
		$_SESSION['authenticated'] = true;
		// Redirect user to index.php
		header("Location: index.php");
	} else {
		echo "<div class='form'>
<h3>Username/password is incorrect.</h3>
<br/>Click here to <a href='login.html'>Login</a></div>";
	}
} else {
	header("Location: login.html");
?>

<?php } ?>
