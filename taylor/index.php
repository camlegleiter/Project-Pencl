<?php

session_name('PenclLogin');
session_start();

?>
<html>
<head>
<?php
	include 'includes/topbar_header.php';
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>CyCal</title>
	</head>
<body>
<?php
	//Must be included at the top of the <body> tag
	
	//define means this is the settings page, which affects the drop-down items in the topbar
	define("SETTINGSPAGE", true);
	include 'includes/topbar.php';
?>
<p>Test</p>
</body>
</html>