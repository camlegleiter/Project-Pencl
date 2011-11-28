<?php
/*
=====================================
	INCLUDES
=====================================
*/
if (!isset($TO_ROOT))
	$TO_ROOT = "../";
	define("PREDIR", "../");	
require $TO_ROOT."includes/membersOnly.php";

/*
=====================================
	SENDING FUNCTIONS
=====================================
*/
function errorMessage($error){
	header("HTTP/1.1 409 ".$error);
	echo $error;
	exit;
}
function successMessage($success){
	echo $success;
	exit;
}

/*
=====================================
	Error Testing
=====================================
*/
if($_POST['error']){
	errorMessage('Error message flag set');
}
if($_POST['success']){
	successMessage('Success message flag set');
}
$action = strtolower((mysql_real_escape_string($_POST['action'])));
$classid = $_GET['classid'];
$name = mysql_real_escape_string($_POST['classname']);
$des = mysql_real_escape_string($_POST['des']);
$pass = mysql_real_escape_string($_POST['pass']);
$owner = $_SESSION['id'];

/*
=====================================
	Print all
=====================================
*/
if($_POST['print']){
	successMessage(print_r($_POST, true));
}

/*
=====================================
	SET DEFAULTS
=====================================
*/
//None... now

/*
=====================================
	ERROR CHECKING
=====================================
*/
if (!empty($classid) && !is_numeric($classid))
{
	errorMessage("classid is not an int");
}


/*
=====================================
	DO WORK
=====================================
*/
//If a class is given, try and find the correct userid
//edit a class
if($action == 'edit'){
	$canedit = true;
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
			errormessage("Error saving class");
		}
		else{
			successmessage('Class has been edited.');
		}
	}
}
//save a class
else if($action == 'add'){
	$cansave = true;
	$nameCheck = mysql_query("SELECT COUNT(*) FROM classes WHERE owner='$owner' AND name='$name'");
	$numrows = mysql_fetch_assoc($nameCheck);
	if($numrows['COUNT(*)'] != 0){
		errorMessage("Class name already used.  Please choose another.");
	}
	mysql_free_result($nameCheck);
	if($name == ''){
		errormessage('No class name given.');
		$cansave = false;
	}
	if($pass == ''){
		$pass = null;
	}
	if($cansave == true){
		$write = mysql_query("INSERT INTO classes (name, description, owner, password) VALUES ('$name','$des','$owner','$pass')");
		if (!$write){
			errormessage("Error saving class");
			
		}
		else{
			successmessage('Class has been saved.');	
		}
	}
}
//delete a class
else if($action == 'delete'){
	$deletebooks = mysql_query("DELETE FROM classbooks WHERE classid='$classid'");
	if (!$deletebooks)
	{
		errormessage('classbooks: '.mysql_error());
	}
	$deletemates = mysql_query("DELETE FROM classmates WHERE classid='$classid'");
	if (!$deletemates)
	{
		errormessage('classmates: '.mysql_error());
	}
	$deleteclass = mysql_query("DELETE FROM classes WHERE id='$classid'");
	if (!$deleteclass)
	{
		errormessage('classes: '.mysql_error());
	}
	successmessage("DELETE FROM classbooks WHERE classid='$classid'\nDELETE FROM classmates WHERE classid='$classid'\DELETE FROM classes WHERE id='$classid'");
}
else if($action == 'addnotes'){

//todo

}
else
	errorMessage('Incorrect post args');
?>


