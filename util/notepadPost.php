<?php
/*
=====================================
	INCLUDES
=====================================
*/
if (!isset($TO_ROOT))
	$TO_ROOT = "../";	
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
	EXTRA FUNCTIONS
=====================================
*/
function buildPath($userid, $notepad){
	return $url = getcwd().'/../notepads/'.$userid.'/'.$notepadid.'/';
}

//Recursive remove directory
function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") 
					rrmdir($dir."/".$object); 
				else 
					unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
		return true;
	}
	return false;
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
$action = strtolower((mysql_real_escape_string($_POST['action']));
$userid = mysql_real_escape_string($_SESSION['id']);
$notepadid = mysql_real_escape_string($_POST['notepadid']);
$notepadname = mysql_real_escape_string($_POST['notepadname']);
$notepaddesc = mysql_real_escape_string($_POST['notepaddesc']);
$content = $_POST['content'];

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
if (!is_int($notepadid))
{
	errorMessage("notepadid is not an int");
}

/*
=====================================
	DO WORK
=====================================
*/
if($action == 'save'){
	//Add html to file
	$path = buildPath($userid, $notepadid);
	$file = fopen($path.$notepadid.'.html', 'w');
	if ($!file)
		errorMessage("Error saving notepad");
	if (!fwrite($file, $content))
		errorMessage("Error saving notepad");
	fclose($file);
	//Set value in SQL
	$padCheck = mysql_query("SELECT COUNT(*) FROM notepads WHERE userid='$userid' AND id='$notepadid'");
	$numrows = mysql_fetch_assoc($padCheck);
	if($numrows['COUNT(*)'] != 0){
		//Already in database, update the value
		$updatePad = mysql_query("UPDATE notepads SET modified=NOW() WHERE userid='$userid' AND id='$notepadid'");
		if (!$updatePad)
			errorMessage("Error saving notepad");
		//mysql_free_result($updatePad);
	}
	else
	{
		//Create new entry
		if (empty($notepadname))
			errorMessage("No notepad name given");
		$insertPad = mysql_query("INSERT INTO notepads (userid, name, description, created, modified) VALUES ('$userid','$notepadname','None',NOW(),NOW())");
		if (!$insertPad)
			errorMessage("Error saving notepad");
		//mysql_free_result($insertPad);
	}
	mysql_free_result($padCheck);

	successMessage('Content Saved');
}
else if($action == 'load'){
	//Grab file from file path
	$path = buildPath($userid, $notepad);
	$file = $path.$notepadid.'.html';
	successMessage(file_get_contents($file));
}
else if($action == 'delete'){
	$path = buildPath($userid, $notepad);
	if (!rrmdir($path))
		errorMessage("Error deleting notepad");
	successMessage('Notepad deleted');
}
else if($action == 'rename'){
	//Rename notepad, or change description
	if (empty($notepadname) && empty($notepaddesc))
		errorMessage("Must give new name or new description");
	
	//Construct our query string
	$updateString = "";
	if (!empty($notepadname))
		$updateString .= "name='$notepadname'";
	if (!empty($notepadname) && !empty($notepaddesc))
		$updateString .= ", ";
	if (!empty($notepaddesc))
		$updateString .= "description='$notepaddesc'";
		
	$updatePad = mysql_query("UPDATE notepads SET $updateString WHERE userid='$userid' AND id='$notepadid'");
	if (!$updatePad)
		errorMessage("Error updating notepad");
	successMessage("Notepad updated");
}
else
	errorMessage('Incorrect post args');
?>


