<?php
define('INCLUDE_CHECK',true);

// Those two files can be included only if INCLUDE_CHECK is defined
require "includes/connect.php";
require 'includes/functions.php';

session_name('tzLogin');
// Starting the session

session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks

session_start();

if($_SESSION['id'] && !isset($_COOKIE['tzRemember']) && !$_SESSION['rememberMe'])
{
	// If you are logged in, but you don't have the tzRemember cookie (browser restart)
	// and you have not checked the rememberMe checkbox:

	$_SESSION = array();
	session_destroy();
	
	// Destroy the session
}


if(isset($_GET['logoff']))
{
	$_SESSION = array();
	session_destroy();
	
	header("Location: index.php");
	exit;
}

if($_POST['submit']=='Login')
{
	// Checking whether the Login form has been submitted
	
	$err = array();
	// Will hold our errors
	
	
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err))
	{
		mysql_connect($db_host, $db_user, $db_pass) or die('Error connecting to mysql, try again later.');
		mysql_select_db($db_name);
		
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		$_POST['password'] = mysql_real_escape_string($_POST['password']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		
		// Escaping all input data
		$result = mysql_query("SELECT id,usr FROM tz_members WHERE usr='{$_POST['username']}' AND pass='".md5($_POST['password'])."'");

		//TODO : REMOVE AT PRODUCTION (SECURITY RISK)
		if (!$result) {
		    die('Invalid query: ' . mysql_error());
		}
		
		$row = mysql_fetch_assoc($result);

		if($row['usr'])
		{
			// If everything is OK login
			
			$_SESSION['usr']=$row['usr'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['rememberMe'] = $_POST['rememberMe'];
			
			// Store some data in the session
			
			setcookie('tzRemember',$_POST['rememberMe']);
			
			//Redirect to members page
			header("Location: registered.php");
			exit;
		}
		else $err[]='Wrong username and/or password!';
		
		mysql_free_result($result);
	}
	
	if($err)
	$_SESSION['msg']['login-err'] = implode('<br />',$err);
	// Save the error messages in the session

	header("Location: index.php");
	exit;
}
else if($_POST['submit']=='Register')
{
	// If the Register form has been submitted
	
	$err = array();
	
	if(strlen($_POST['username'])<4 || strlen($_POST['username'])>32)
	{
		$err[]='Your username must be between 3 and 32 characters!';
	}
	
	if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username']))
	{
		$err[]='Your username contains invalid characters!';
	}
	
	//if(!checkEmail($_POST['email']))
	//{
	//	$err[]='Your email is not valid!';
	//}
	if(strlen($_POST['password']) < 8)
	{
		$err[]='Your password needs to be 8 characters or more!';
	}
	//CHECK FOR ILLEGAL PASSWORDS (SQL INJECTION)
	
	if($_POST['betaKey'] != 'eraser')
	{
		$err[]='You have entered an invalid Beta Key!';
	}
	
	if(!count($err))
	{
		// If there are no errors
		
		//$pass = substr(md5($_SERVER['REMOTE_ADDR'].microtime().rand(1,100000)),0,6);
		// Generate a random password
		
		$link = mysql_connect($db_host, $db_user, $db_pass) or die('Error connecting to mysql, try again later.');
		mysql_select_db($db_name);
		
		//$_POST['email'] = mysql_real_escape_string($_POST['email']);
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		$_POST['password'] = mysql_real_escape_string($_POST['password']);
		// Escape the input data
		
		
		$result = mysql_query("	INSERT INTO tz_members(usr,pass,email,regIP,dt)
						VALUES(
						
							'".$_POST['username']."',
							'".md5($_POST['password'])."',
							'testAccount@localhost',
							'".$_SERVER['REMOTE_ADDR']."',
							NOW()
							
						)");
		
		//TODO : REMOVE AT PRODUCTION (SECURITY RISK)
		if (!$result) {
		    die('Invalid query: ' . mysql_error());
		}
		
		if(mysql_affected_rows($link)==1)
		{
			//send_mail(	'donotreply@pencl.me',
			//			$_POST['email'],
			//			'Pencl - Registration',
			//			'Your password is: '.$pass);

			//$_SESSION['msg']['reg-success']='We sent you an email with your new password!';
			$_SESSION['msg']['reg-success']='Congratulations! You\'re in!';
		}
		else $err[]='This username is already taken! Affected rows: '.mysql_affected_rows($link);
	}

	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
	}	
	
	header("Location: index.php");
	exit;
}

$script = '';

if($_SESSION['msg'])
{
	$subscript = '';
	
	// The script below shows the sliding panel on page load
	if ($_SESSION['msg']['reg-err'])
	{
		$subscript = '$("#toggleLogin").hide();$("#toggleRegister").show();';
	}
	else if ($_SESSION['msg']['log-err'])
	{
		$subscript = '$("#toggleRegister").hide();$("#toggleLogin").show();';
	}
	
	$script = '
	<script type="text/javascript">
	
		$(function(){
		
			$("div#panel").show();
			$("#toggle a").toggle();
			
			'.$subscript.'			
		});
	
	</script>';
	
}

?>
