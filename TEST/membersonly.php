<?php
include 'session.php';
session_start();
//if the user has not logged in
if(!isLoggedIn())
{
	header('Location: login.php');
	die();
}
//page content follows
echo ("<h>In the secret area!</h>");
?>
