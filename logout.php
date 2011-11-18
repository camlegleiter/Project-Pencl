<?php
//Set session name
session_name('PenclLogin');

//Start it
session_start();

//Clear the session array
$_SESSION = array();
//Destory the session
session_destroy();

//Redirect to the homepage
header("Location: index.php");

//Quit parsing PHP
exit;


?>