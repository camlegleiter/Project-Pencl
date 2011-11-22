<?php
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

define('INCLUDE_CHECK',true);

// Those two files can be included only if INCLUDE_CHECK is defined
require "includes/connect.php";
require 'includes/functions.php';

function addUsertoClass()
{

	//Join em in
	//$classRow = mysql_query("INSERT INTO name,description,password FROM classes WHERE id='$classid'");
	
	//TODO: Ad user as a classmate
	
	header("Location: noteselection.php");
	exit;
}

$classid = mysql_real_escape_string($_GET['classid']);
$classRow = mysql_query("SELECT name,description,password,owner FROM classes WHERE id='$classid'");
$row = mysql_fetch_assoc($classRow);

$classarr = array();
if ($row)
{
	$classarr['name'] = $row['name'];
	$classarr['description'] = $row['description'];
	$classarr['password'] = $row['password'];
	$classarr['owner'] = $row['owner'];
	
	if ($row['owner'] == $_SESSION['id'])
	{
		//Already in the class
		header("Location: noteselection.php");
		exit;
	}
	
	//TODO: See if they are already in the class
	
	if (strlen($row['password']) == 0)
	{
		addUsertoClass();
	}
	else
	{
		//Check to see if the password is the same, or matches
		if ($_POST['submit'])
		{
			if (strcmp($row['password'],$_POST['password']) == 0)
			{
				addUsertoClass();
			}
			else
			{
				//Dont match
				$_SESSION['msg']['err'] = "That password doesn't match. Try again.";
			}
		}
	}
}
else
{
	echo mysql_error();
	$classarr['name'] = 'Error (Name)';
	$classarr['description'] = 'Error (Description)';
	$classarr['password'] = NULL;
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
<!-- Load jQuery -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("jquery", "1");
</script>

<?php
include 'includes/topbar_header.php';
include 'includes/tracker.php';
?>
</head>

<body onload="$('#classPassword').focus()">
<?php
//Must be first thing in the <body> tag to function correctly
include 'includes/topbar.php';
?>
<div class="bookcontainer">		
	<div class="book">
		<div class="page">
			<?php 
				echo '<h1>Join class:</h1><h1>'.$classarr['name'].'</h1>';
				echo '<p>'.$classarr['description'].'</p>';
			?>
		</div>
		<div class="middle"><br></div>
		<div class="page">
			<h1>Password</h1>
			<br>
			<form action="" method="post">
				<input id="classPassword" name="password" type="text"><br>
				<input class="submit" name="submit" type="submit" value="Join class">
			</form>
			<br>
			<?php
				if($_SESSION['msg']['err'])
				{
					echo '<div class="error"><p>'.$_SESSION['msg']['err'].'</p></div>';
					unset($_SESSION['msg']['err']);
				}
			?>
		</div>
		<div style="clear:both"></div>
	</div>
	
</div>
</body>
</html>