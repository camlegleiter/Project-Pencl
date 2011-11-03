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
if($_POST['error']){
	errorMessage('Error message flag set');
}
if($_POST['success']){
	successMessage('Success message flag set');
}

$action = mysql_real_escape_string($_POST['action']);
$notpadid = mysql_real_escape_string($_POST['notpadid']);
$content = mysql_real_escape_string($_POST['content']);

if($action == 'get'){
	successMessage('Content loaded');
}
if($action == 'load'){
	successMessage('Content loaded');
}
	errorMessage('Incorrect post args');
?>