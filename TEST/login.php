<?php
session_start(); //must call session_start before using any $_SESSION variables
include 'session.php';
$username = $_POST['username'];
$password = $_POST['password'];
//connect to the database here
$username = mysql_escape_string($username);
$query = "SELECT password, salt
		FROM users
		WHERE username = '$username';";
$result = mysql_query($query);
if(mysql_num_rows($result) < 1) //no such user exists
{
	header('Location: login_form.php');
	die();
}
$userData = mysql_fetch_array($result, MYSQL_ASSOC);
$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );
if($hash != $userData['password']) //incorrect password
{
	header('Location: login_form.php');
	die();
}
else
{
	validateUser(); //sets the session data for this user
}
//redirect to another page or display "login success" message?>