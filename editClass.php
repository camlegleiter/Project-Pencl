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
	$classid = $_GET['classid'];
	if($_POST['edit']){
		$canedit = true;
		$name = mysql_real_escape_string($_POST['classname']);
		$des = mysql_real_escape_string($_POST['des']);
		$pass = mysql_real_escape_string($_POST['pass']);
		$owner = $_SESSION['id'];
		if($name == ''){
			adderror('No class name given.');
			$canedit = false;
		}
		if($pass == ''){
			$pass = null;
		}
		if($canedit == true){
		$write = mysql_query("UPDATE classes SET name='$name' , description='$des' , password='$pass' WHERE id='$classid'");
		if (!$write){
			adderror("Error saving class");
		}
		else{
			addsuccess('Class has been edited.');
			header( 'Location: classes.php' );
		}
		}
	}
	$getclass = mysql_query("SELECT * FROM classes WHERE id= '$classid'");
	$row = mysql_fetch_assoc($getclass);
	mysql_free_result($getclass);

		
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
		echo "<h1>Edit Class.</h1>";
		
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
	<br>
	<form class="clearfix" action="" method="post">
		<div>
		<label class="grey" for="classname">Class Name:</label>
		<input class="field" type="text" name="classname" id="text" size="23" value ="<?php echo $row['name'] ?>"/>
		</div>
		<div>
		<label class="grey" for="Description">Description:</label>
		<input class="field" type="text" name="des" id="text" size="23" value ="<?php echo $row['description'] ?>"/>
		</div>
		<div>
		<label class="grey" for="pass">Password(Optional):</label>
		<input class="field" type="text" name="pass" id="text" size="23" value ="<?php echo $row['password'] ?>"/>
		</div>
		<input type="submit" name="edit" value="Edit Class" />
        <input type="button" name="cancel" value="Cancel" onClick="window.history.back()" />
	</form>
<!--</div>-->
</div>
</div>
</div>
</body>
</html>
