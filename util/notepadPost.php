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
	EXTRA FUNCTIONS
=====================================
*/
function buildPath($userid, $notepadid){
	//return $url = getcwd().'\\..\\notepads\\'.$userid.'\\'.$notepadid.'\\';
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

function findUserid($notebookid,$classid)
{
	//Reference our global variable
	global $userid;
	
	//Make sure user is in the class
	$padCheck = mysql_query("SELECT COUNT(*) FROM classmates WHERE userid='$userid' AND classid='$classid'");
	$numrows = mysql_fetch_assoc($padCheck);
	mysql_free_result($padCheck);
	if($numrows['COUNT(*)'] == 0)
	{
		//This notebook isnt with this class
		errorMessage("You are not enrolled in this class");
	}
	
	//Make sure that this notebook is linked to the class
	$padCheck = mysql_query("SELECT COUNT(*) FROM classbooks WHERE notebookid='$notebookid' AND classid='$classid'");
	$numrows = mysql_fetch_assoc($padCheck);
	if($numrows['COUNT(*)'] == 0)
	{
		mysql_free_result($padCheck);
		//This notebook isnt with this class
		errorMessage("This notebook is not from the class");
	}
	else
	{
		mysql_free_result($padCheck);
		//Get the userid of the notebook
		$notebook = mysql_query("SELECT userid FROM notebooks WHERE id='$notebookid'");
		$row = mysql_fetch_assoc($notebook);
		if($row['userid'])
		{
			$userid = $row['userid'];
			mysql_free_result($notebook);
		}
		else
		{
			mysql_free_result($notebook);
			errorMessage("Error finding user with notepad id: ".$notebookid);
		}
	}
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
//.../htdocs/pencl/notepads/<userid>/<notepadId>/<notepadid>.html
$action = strtolower((mysql_real_escape_string($_POST['action'])));
$userid = mysql_real_escape_string($_SESSION['id']);
$notepadid = mysql_real_escape_string($_POST['notepadid']);
$classid = mysql_real_escape_string($_POST['classid']);
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
if (!empty($classid))
{
	//This sets the global variable to $userid if found
	findUserid($notepadid,$classid);
}


if($action == 'save'){
	//Add html to file
	$path = buildPath($userid, $notepadid);
	//Create our directory if not made
	if (!is_dir($path))
		mkdir($path, 0777, true);

	$content = str_replace("\\\"", "\"", $content);
	$content = str_replace("\\'", "'", $content);
	$content = str_replace("\\\\", "\\", $content);
	$content = htmlentities($content, ENT_QUOTES);
		
	$file = fopen($path.$notepadid.'.html', 'w');
	if (!$file)
		errorMessage("Error saving notepad (-1)");
	if (fwrite($file, $content) === false)
		errorMessage("Error saving notepad (-2)");
	fclose($file);
	
	//Set value in SQL
	$padCheck = mysql_query("SELECT COUNT(*) FROM notebooks WHERE userid='$userid' AND id='$notepadid'");
	$numrows = mysql_fetch_assoc($padCheck);
	if($numrows['COUNT(*)'] != 0){
		//Already in database, update the value
		$updatePad = mysql_query("UPDATE notebooks SET modified=NOW() WHERE userid='$userid' AND id='$notepadid'");
		if (!$updatePad)
			errorMessage("Error saving notepad");
		//mysql_free_result($updatePad);
	}
	else
	{
		//Create new entry
		if (empty($notepadname))
			errorMessage("No notepad name given");
		$insertPad = mysql_query("INSERT INTO notebooks (userid, name, description, created, modified) VALUES ('$userid','$notepadname','$notepaddesc',NOW(),NOW())");
		if (!$insertPad)
			errorMessage("Error saving notepad (-3)");
	}
	mysql_free_result($padCheck);

	successMessage('Content Saved');
}
else if($action == 'load'){
	//Grab file from file path
	$path = buildPath($userid, $notepadid);
	$file = $path.$notepadid.'.html';
	
	$padName = mysql_query("SELECT name FROM notebooks WHERE userid='$userid' AND id='$notepadid'");
	$row = mysql_fetch_assoc($padName);
	if($row['name']){
		$name = $row['name'];
	}
	else
	{
		$name = "Error fetching notepad name!";
	}
	mysql_free_result($padName);
	
	$contents = file_get_contents($file);
	if ($contents === false)
		$contents = "<p>Couldn't load file!</p>";

	$contents = html_entity_decode($contents);
	
	$arr = array(
		"notepadname" => $name,
		"content" => $contents
		);
	successMessage(json_encode($arr));
}
else if($action == 'delete'){
	$deletePad = mysql_query("DELETE FROM notebooks WHERE userid='$userid' AND id='$notepadid'");
	$deletedRows = mysql_affected_rows();
	if ($deletedRows > 1)
		errorMessage("Error deleting notepad (-1)");
	else if ($deletedRows < 1)
		errorMessage("Error deleting notepad (-2)");
	$path = buildPath($userid, $notepadid);
	if (!rrmdir($path))
		errorMessage("Error deleting notepad (-3)");
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
	
	//Make sure we're not making another notepad with the same name
	$padCheck = mysql_query("SELECT COUNT(*) FROM notebooks WHERE userid='$userid' AND name='$notepadname' AND NOT id=$notepadid");
	$numrows = mysql_fetch_assoc($padCheck);
	if($numrows['COUNT(*)'] != 0){
		errorMessage("Notepad name already used.  Please choose another.");
	}
	mysql_free_result($padCheck);

	//Update!
	$updatePad = mysql_query("UPDATE notebooks SET $updateString WHERE userid='$userid' AND id='$notepadid'");
	if (!$updatePad)
		errorMessage("Error updating notepad");
	successMessage("Notepad updated");
}
else if($action == 'getrename'){
	$padName = mysql_query("SELECT name,description FROM notebooks WHERE userid='$userid' AND id='$notepadid'");
	$row = mysql_fetch_assoc($padName);
	if($row['name'])
	{
		$name = $row['name'];
	}
	else
	{
		$name = "Error fetching notepad name!";
	}
	if ($row['description'])
	{
		$desc = $row['description'];
	}
	else
	{
		$desc = "Error fetching notepad description!";
	}
	mysql_free_result($padName);

	$arr = array("notepadname" => $name,
				 "notepaddesc" => $desc);
	successMessage(json_encode($arr));
}

else if($action == 'create'){
	//Rename notepad, or change description
	if (empty($notepadname))
		errorMessage("Must give a name to the notepad");
	
	//Make sure we're not making another notepad with the same name
	$padCheck = mysql_query("SELECT COUNT(*) FROM notebooks WHERE userid='$userid' AND name='$notepadname'");
	$numrows = mysql_fetch_assoc($padCheck);
	if($numrows['COUNT(*)'] != 0){
		errorMessage("Notepad name already used.  Please choose another.");
	}
	mysql_free_result($padCheck);
	
	//Create new entry
	$insertPad = mysql_query("INSERT INTO notebooks (userid, name, description, created, modified) VALUES ('$userid','$notepadname','$notepaddesc',NOW(),NOW())");
	if (!$insertPad)
		errorMessage("Error creating notepad (-3)");
				
	//Get the notepad ID
	$padID = mysql_query("SELECT id FROM notebooks WHERE userid='$userid' AND name='$notepadname'");
	if (!$padID)
		errorMessage("Error creating notepad (-4)");
	$row = mysql_fetch_assoc($padID);
	if($row['id']){
		$notepadid = $row['id'];
	}
	else
	{
		errorMessage("Error creating notepad (-5)");
	}
	mysql_free_result($padID);

		
	//Add html to file
	$path = buildPath($userid, $notepadid);
	//Create our directory if not made
	if (!is_dir($path))
		mkdir($path, 0777, true);
	$file = fopen($path.$notepadid.'.html', 'w');
	if (!$file)
		errorMessage("Error creating notepad (-1)");
	//if (!fwrite($file, ''))
	//	errorMessage("Error creating notepad (-2)");
	fclose($file);
	
	$arr = array(notepadid => $notepadid);
	successMessage(json_encode($arr));
}

else
	errorMessage('Incorrect post args');
?>


