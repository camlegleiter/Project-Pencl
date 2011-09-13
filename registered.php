<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/index.css" media="screen" />

<?php
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';
?>

</head>

<body>
<?php
//Must be first thing in the <body> tag to function correctly
include 'includes/headerbar.php';
?>

<div id="page">

	<h3>Welcome to Pencl <?php echo $_SESSION['usr']?>!</h3> 
	<h4>We are currently working on the main application, so please come back 
	later to try it out!</h4>
	
	<hr />
	
	<h3>Settings</h3>
	<h4>Settings may go here soon?</h4>	
	<hr />   
</div>

<?php
//Must be last thing in the <body> tag to function correctly
include 'includes/footerbar.php';
?>

</body>
</html>
