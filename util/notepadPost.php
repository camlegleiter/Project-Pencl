<?php
//print_r($_POST);
//print_r($_GET);
//for errors, use 409 error
//define('PREDIR', '../');
//include "../includes/membersOnly.php";

function errorMessage($error){
header("HTTP/1.1 409 ".$error);
echo $error;
exit;
}
function successMessage($success){
echo $success;
exit;
}
function buildPath($userid, $notepad){
return $url = getcwd().'/../notepads/'.$userid.'/'.$notepadid;
}
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
$content = mysql_real_escape_string($_POST['content']);

if($action == 'save'){
	$path = buildPath($userid, $notepad, $content);
	$file = fopen($path.'/'.$notepadid.'.html', 'w');
	fwrite($file, $content);
	fclose($file);
	successMessage('Content Saved');
}
if($action == 'load'){
	successMessage('Content loaded');
}
	errorMessage('Incorrect post args');
?>


