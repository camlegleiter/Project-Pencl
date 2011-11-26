<?php
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

define('INCLUDE_CHECK',true);

// Those two files can be included only if INCLUDE_CHECK is defined
require "includes/connect.php";
require 'includes/functions.php';

function addUsertoClass($userid,$classid)
{

	//Join em in
	$classRow = mysql_query("INSERT INTO classmates (userid,classid) VALUES ('$userid','$classid')");
	
	$_SESSION['msg']['success'] = 'You are now enrolled in this class.';
	header("Location: noteselection.php");
	exit;
}

$classid = mysql_real_escape_string($_GET['classid']);
$classRow = mysql_query("SELECT name,description,password,owner FROM classes WHERE id='$classid'");
$row = mysql_fetch_assoc($classRow);
mysql_free_result($classRow);

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
		$_SESSION['msg']['err'] = 'You are teaching this class.';
		header("Location: noteselection.php");
		exit;
	}
	
	//TODO: See if they are already in the class
	$userid = mysql_real_escape_string($_SESSION['id']);
	$classmateRow = mysql_query("SELECT COUNT(*) FROM classmates WHERE classid='$classid' AND userid='$userid'");
	$cmrow = mysql_fetch_assoc($classmateRow);
	mysql_free_result($classmateRow);
	
	if ($cmrow['COUNT(*)'] != 0)
	{
		//User is already enrolled in the class
		$_SESSION['msg']['err'] = 'You are already enrolled in this class.';
		header("Location: noteselection.php");
		exit;
	}
	
	if (strlen($row['password']) == 0)
	{
		addUsertoClass($userid,$classid);
	}
	else
	{
		//Check to see if the password is the same, or matches
		if ($_POST['submit'])
		{
			if (strcmp($row['password'],$_POST['password']) == 0)
			{
				addUsertoClass($userid,$classid);
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
	$classarr['name'] = 'N/A';
	$classarr['description'] = '';
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