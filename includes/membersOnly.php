<?php
//Check if the user is logged in.
if (!$_SESSION['id'])
{
	//If not, redirect them to the main page
	header("Location: index.php");
	exit;
}
?>