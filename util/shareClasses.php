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
	header("HTTP/1.1 409".$error);
	echo $error;
	exit;
}
function successMessage($success){
	echo $success;
	exit;
}

$userid = mysql_real_escape_string($_SESSION['id']);
$classes = json_decode(stripslashes($_POST['classes']), true);
$notepadid = mysql_real_escape_string($_POST['notepadid']);

foreach ($classes as $clazz) {
	// See if the current class already has the class
	$padEntry = mysql_query("SELECT * FROM classbooks WHERE classid='$clazz' and notebookid='$notepadid';");
	$row = mysql_fetch_assoc($padEntry);

	// The class does not already have the selected note
	if (empty($row)) {
		$updatePad = mysql_query("INSERT INTO classbooks (classid, notebookid) VALUES ('$clazz', '$notepadid');");
	} else {
		// The class already does - warn the user of this
		$classbook = mysql_query("SELECT name FROM classes WHERE id='$clazz';");
		$book = mysql_fetch_assoc($classbook);
		errorMessage("The class " . $book['name'] . " already contains this note");
	}
}


?>
