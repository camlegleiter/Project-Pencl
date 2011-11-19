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
<!-- Load jQuery -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("jquery", "1");
</script>

<?php
include 'includes/topbar_header.php';
include 'includes/tracker.php';
?>
</head>

<body onload="
<?php 
	if ($_SESSION['msg']['reg-err'])
		echo "$('#usernameRegister').focus()";
	else
		echo "$('#usernameLogin').focus()";
?>
">
<?php
//Must be first thing in the <body> tag to function correctly
include 'includes/topbar.php';
?>

<br>
<br>
<div class="login">		
	<div class="book">
		<div class="page">
			<h1>Register</h1>
			<br>
			<form action="" method="post">
				<p>Username:</p>
				<input id="usernameRegister" name="username" type="text"><br>
				<p>Password:</p>
				<input name="password" type="password"><br>
				<p>Confirm Password:</p>
				<input name="passwordagain" type="password"><br>
				<p>Beta Key:</p>
				<input name="betaKey" type="text"><br>
				<input class="submit" name="submit" type="submit" value="Register">
			</form>
			<br>
			<?php
				if ($_SESSION['msg']['reg-success'])
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
		</div>
		<div class="middle"><br></div>
		<div class="page">
			<h1>Login</h1>
			<br>
			<form action="" method="post">
				Username: <input id="usernameLogin" name="username" type="text"><br>
				Password: <input name="password" type="password"><br>
				<input class="submit" name="submit" type="submit" value="Login">
			</form>
			<br>
			<?php
				if($_SESSION['msg']['login-err'])
				{
					echo '<div class="error"><p>'.$_SESSION['msg']['login-err'].'</p></div>';
					unset($_SESSION['msg']['login-err']);
				}
			?>
		</div>
		<div style="clear:both"></div>
	</div>
	
</div>
</body>
</html>