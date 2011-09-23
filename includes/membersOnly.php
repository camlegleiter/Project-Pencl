<?php
require "includes/connect.php";

function checkToken($userid, $token)
{
	$valid = false;
	if ($userid || $token)
	{
		$tokenOut = mysql_query("SELECT token FROM users WHERE 
				userid=".$userid."
				");
		
		$tokenrow = mysql_fetch_assoc($tokenOut);
		
		if ($tokenrow['token'])
		{
			if ($token == $tokenrow['token'])
				$valid = true;
		}
		
		mysql_free_result($tokenOut);
	}
	return $valid;
}

//Check if the user is logged in.
if (!checkToken($_SESSION['id'], $_SESSION['token']))
{
	//If not, redirect them to the main page
	header("Location: index.php");
	exit;
}
?>