<?php
include 'includes/headerbarFunctions.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/index.css" media="screen" />

</head>

<body>
<?php
include 'includes/headerbar.php';
?>

<div id="page">

	<h3>Members</h3> 

	    <?php
	if($_SESSION['id'])
	echo '<h4>Hello, '.$_SESSION['usr'].'! You are registered and logged in!</h4>';
	else echo '<h4>Please, <a href="index.php">login</a> and come back later!</h4>';
    ?>    
       
</div>

<!-- Feel free to remove this footer -->

<div id="footer">
	<div class="tri"></div>
	<h1>Pencl - The web-based note taking application.</h1>
</div>

</body>
</html>
