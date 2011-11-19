<?php
//Must be on top of everything to function correctly
include 'includes/registerFunctions.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Pencl - Coming Soon</title>
<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/login.css" media="screen">
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
?>

<br>
<br>
<div class="login">		
	<div class="book">
		<div class="page">
			<h1>Register (Doesn't work yet)</h1>
			<br>
			<form action="" method="post">
				<p>Username:</p>
				<input name="username" type="text"><br>
				<p>Password:</p>
				<input name="password" type="password"><br>
				<p>Confirm Password:</p>
				<input name="passwordagain" type="password"><br>
				<p>Beta Key:</p>
				<input name="betaKey" type="text"><br>
				<input class="submit" name="submit" type="submit" value="Register">
			</form>
		</div>
		<div class="middle"><br></div>
		<div class="page">
			<h1>Login</h1>
			<br>
			<form action="" method="post">
				Username: <input name="username" type="text"><br>
				Password: <input name="password" type="password"><br>
				<input class="submit" name="submit" type="submit" value="Login">
			</form>
		</div>
		<div style="clear:both"></div>
	</div>
	
</div>
</body>
</html>