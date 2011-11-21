<?php
define('INCLUDE_CHECK',true);

// Those two files can be included only if INCLUDE_CHECK is defined
require "includes/connect.php";
require 'includes/functions.php';
include 'includes/membersOnly.php';

function adderror($error){
	global $errorarray;
	$errorarray[] = $error;
}

function addsuccess($success){
	global $successarray;
	$successarray[] = $success;
}

	$userInfo = mysql_query("SELECT username,email FROM users WHERE 
		userid=".$_SESSION['id']."
		");

	$userRow = mysql_fetch_assoc($userInfo);

	if (!$userRow)
		die('Error getting user information.');

	if ($_POST['save'])
	{
		$cansave = true;
		
		//Check old hash
		$userSalt = getUserSalt($_SESSION['id']);
		$oldHash = getPassword($_SESSION['usr'], $_POST['oldpass'], $userSalt);
		
		//Check oldHash against the stored hash
		$passOut = mysql_query("SELECT password FROM users WHERE 
			userid=".mysql_real_escape_string($_SESSION['id'])."
			");
		
		$passrow = mysql_fetch_assoc($passOut);
		
		if ($passrow['password'])
		{
			if ($oldHash != $passrow['password'])
			{
				adderror("Incorrect password. Cannot save.");
				$cansave = false;
			}
		}
		else
		{
			adderror("Incorrect password. Cannot save.");
			$cansave = false;
		}
		
		mysql_free_result($passOut);
		
		//==== Password ===================================================

		if ($_POST['newpass'])
		{			
			if ($_POST['oldpass'] == $_POST['newpass'])
			{
				adderror("Old and new password cannot be the same.");
				$cansave = false;
			}
			$_POST['newpass'] = mysql_real_escape_string($_POST['newpass']);
			if ($_POST['newpass'] != $_POST['newpass2'])
			{
				adderror("Passwords do not match.");
				$cansave = false;
			}
			if(strlen($_POST['newpass']) < 8)
			{
				adderror("Your new password needs to be 8 characters or more!");
				$cansave = false;
			}
		}
		
		//==== Email ===================================================
		
		if ($userRow['email'] != $_POST['cemail'])
		{
			if (!checkEmail($_POST['cemail']))
			{
				adderror("Not a valid email.");
				$cansave = false;
			}
		}
			
		//==== Saving ===================================================
		
		//Everything checks out
		//Change the password
		if ($cansave)
		{
			//Save password, if new pass
			if ($_POST['newpass'])
			{
				$newHash = getPassword($_SESSION['usr'], $_POST['newpass'], $userSalt);
	
				$passIn = mysql_query("UPDATE users SET 
					password='".$newHash."' WHERE 
					userid=".mysql_real_escape_string($_SESSION['id'])."
					");
				
				if ($passIn)
				{
					addsuccess("Password changed!");
				}
				else
				{
					adderror("Error changing your password!");
				}
			}
			
			//Save email, if new email
			if ($userRow['email'] != $_POST['cemail'])
			{
				$emailIn = mysql_query("UPDATE users SET 
					email='".mysql_real_escape_string($_POST['cemail'])."' WHERE 
					userid=".mysql_real_escape_string($_SESSION['id'])."
					");
				
				if ($emailIn)
				{
					addsuccess("Email changed!");
					$userRow['email'] = mysql_real_escape_string($_POST['cemail']);
				}
				else
				{
					adderror("Error changing your email!");
				}
			
			}
			
			
		}
	}
	else if ($_POST['clearnotes'])
	{
		adderror("Nope. Chuck Testa.");
	}
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/book.css" media="screen">

<?php
//Put this at the end of the <head> tag to track
include 'includes/topbar_header.php';
include 'includes/tracker.php';
?>
</head>

<body>
<?php
//Must be first thing in the <body> tag to function correctly
include 'includes/topbar.php';
?>
<!--<div id="pagewide">-->
<div class="bookcontainer">		
	<div class="book">
		<div class="page">
	<?php
		echo "<h1>User settings for ".$userRow['username']."</h1>";
		
		if ($errorarray)
		{
			foreach($errorarray as $value) 
				echo "<p class='error'>$value</p>";
		}
		if ($successarray)
		{
			foreach($successarray as $svalue)
				echo "<p class='success'>$svalue</p>";
		}
		unset($errorarray);
		unset($successarray);
	?>
	
	<form class="clearfix" action="" method="post">
		<h3>Change password</h3>
		<div>
		<label class="grey" for="oldpass">Current:</label>
		<input class="field" type="password" name="oldpass" id="password" size="23" />
		</div>
		<div>
		<label class="grey" for="newpass">New:</label>
		<input class="field" type="password" name="newpass" id="password" size="23" />
		</div>
		<div>
		<label class="grey" for="newpass">Retype new:</label>
		<input class="field" type="password" name="newpass2" id="password" size="23" />
		</div>
		<h3>Change Email</h3>
		<div>
		<label class="grey" for="email">Email:</label>
		<input class="field" type="text" name="cemail" id="cemail" value="<?php echo $userRow['email'] ?>" size="23" />
		</div>
		<input type="submit" name="save" value="Save" />
		<input type="submit" name="clearnotes" value="Delete Notebooks" />
        <input type="button" name="cancel" value="Cancel" onClick="window.history.back()" />
	</form>
<!--</div>-->
</div>
</div>
</div>
</body>
</html>
