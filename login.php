<?php
//Must be on top of everything to function correctly
include 'includes/registerFunctions.php';
//Test
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">
<?php
include 'includes/topbar_header.php';
include 'includes/tracker.php';
?>
</head>

<body>
<?php
//Must be first thing in the <body> tag to function correctly
include 'includes/topbar.php';


if($_SESSION['msg']['login-err'])
					{
						echo '<div class="error"><p>'.$_SESSION['msg']['login-err'].'</p></div>';
						unset($_SESSION['msg']['login-err']);
					}
					else if ($_SESSION['msg']['reg-success'])
					{
						echo '<div class="success"><p>'.$_SESSION['msg']['reg-success'].'</p></div>';
						unset($_SESSION['msg']['reg-success']);
					}
					if($_SESSION['msg']['reg-err'])
					{
						echo '<div class="error"><p>'.$_SESSION['msg']['reg-err'].'</p></div>';
						unset($_SESSION['msg']['reg-err']);
					}

?>


<div id="pagewide">		
<form action="" method="post">
	Username: <input name="username" type="text"><br>
	Password: <input name="password" type="password"><br>
	<input name="submit" type="submit" value="Login"></form>

</div>
</body>
</html>
