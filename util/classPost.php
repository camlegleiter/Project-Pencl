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
//.../htdocs/pencl/notepads/<userid>/<notepadId>/<notepadid.html>
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
if (!is_numeric($notepadid) && $action != 'create')
{
	errorMessage("notepadid is not an int");
}
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
	if(action == 'edit'){
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
			header( 'Location: classes.php' );
		}
		}
	}
	//save a class
	if(action == 'save']){
		$cansave = true;
		if($name == ''){
			adderror('No class name given.');
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
				header( 'Location: classes.php' );	
			}
		}
	}
	//delete a class
	if(action == 'delete']){
		$write = mysql_query("INSERT INTO classes (name, description, owner, password) VALUES ('$name','$des','$owner','$pass')");
		$delete = mysql_query("DELETE FROM classes WHERE id='$classid'");
			if (!$delete){
				errormessage("Error saving class");
				
			}
			else{
				header( 'Location: classes.php' );	
			}
		}
	}




else
	errorMessage('Incorrect post args');
?>


