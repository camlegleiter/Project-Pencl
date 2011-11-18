<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
?>
<?php
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">
<?php
//Put this at the end of the <head> tag to track
include 'includes/topbar_header.php';
include 'includes/tracker.php';
?>
</head>

<body>
<?php
//Must be first thing in the <body> tag to function correctly
include 'includes/topbar.php';
?>


<div id="page">

	<h3>Welcome to Pencl <?php echo $_SESSION['usr']?>!</h3> 
	<h4>
		We are currently working on the main application, so please come back 
		later to try it out!
	</h4>
	<h4>&nbsp;
		
	</h4>
	<h4 style="text-align:center">
		<a href="canvas.php">Open New Notepad</a>
	</h4>
	<h4 style="text-align:center">
		<a href="noteselection.php">Notepad Selection</a>
	</h4>
	
	<?php
		$usrlevel = getUserLevel($_SESSION['id']);
		if ($usrlevel == 0 || $usrlevel == 1)
		{
			echo '<h4 style="text-align:center">
					<a href="admin.php">Admin Page</a>
				  </h4>';
		}
	?>
	
	<hr>
	
	<h3>Settings</h3>
	<?php
	include 'settings.php';
	?>	
	<hr>   
</div>

<?php
//Must be last thing in the <body> tag to function correctly
include 'includes/footerbar.php';
?>

</body>
</html>
