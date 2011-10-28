<?php
//for errors, use 409 error
define('PREDIR', '../');
include "../includes/membersOnly.php";

function errorMessage($error){
header("HTTP/1.1 409 Conflict");
echo $error;
exit;
}
function successMessage($success, $content){
echo $content;
echo $success;
exit;
}
if($_POST['error']){
	errorMessage('Error message flag set');
}
if($_POST['success']){
	errorMessage('Success message flag set');
}

$action = $_POST['action'];
$notpadid = $_POST['notpadid'];
$content = $_POST['content'];

if($action == 'get'){
	successMessage('', $content);
}

//$rssCheck = mysql_query("SELECT 1 FROM panel WHERE userid='$userid', rss='$rss'");
//$numrows = mysql_num_rows($rssCheck);
//if($numrows != 0){
//	errorMessage("RSS feed already added");
//}

//mysql_query("INSERT INTO panel VALUES ('','$userid','$rss','$posx','$posy','$sizex','$sizey','$themeid')");

?>