<?php
define('INCLUDE_CHECK',true);

if (!isset($TO_ROOT))
	$TO_ROOT = "../";

// Those two files can be included only if INCLUDE_CHECK is defined
require "includes/connect.php";
require 'includes/functions.php';

session_name('PenclLogin');
// Starting the session

session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks

session_start();

if($_SESSION['id'] && !isset($_COOKIE['CyCalRemember']) && !$_SESSION['rememberMe'])
{
	// If you are logged in, but you don't have the CyCalRemember cookie (browser restart)
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
//$_SESSION['msg']['formtype'] = 'nothing';

if($_POST['submit']=='Login')
{
	// Checking whether the Login form has been submitted
	$_SESSION['msg']['formtype'] = 'login';
	$err = array();
	// Will hold our errors
	
	if(!$_POST['username'] || !$_POST['password'] ||
		$_POST['username'] == 'Username' ||
		$_POST['password'] == 'Password')
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err))
	{
		//mysql_connect($db_host, $db_user, $db_pass) or die('Error connecting to mysql, try again later.');
		//mysql_select_db($db_name);
		
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		$_POST['password'] = mysql_real_escape_string($_POST['password']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		
		// Escaping all input data
		$userSalt = mysql_query("SELECT salt FROM users WHERE 
			username='{$_POST['username']}'
			");

		$saltrow = mysql_fetch_assoc($userSalt);
		if ($saltrow['salt'])
		{		
			$result = mysql_query("SELECT userid,username FROM users WHERE 
				username='{$_POST['username']}' AND 
				password='".getPassword($_POST['username'], $_POST['password'], $saltrow['salt'])."'
				");
	
			//TODO : REMOVE AT PRODUCTION (SECURITY RISK)
			if (!$result) {
			    die('Invalid query (65): ' . mysql_error());
			}
			
			
			$row = mysql_fetch_assoc($result);
	
			if($row['username'])
			{
				// If everything is OK login
				
				$_SESSION['usr']=$row['username'];
				$_SESSION['id'] = $row['userid'];
				
				$newToken = getSHA();
				$giveToken = mysql_query("UPDATE users SET token='".$newToken."' WHERE 
					userid=".$row['userid']."
					");
				if ($giveToken)
				{
					$_SESSION['token'] = $newToken;
					$_SESSION['rememberMe'] = $_POST['rememberMe'];
					
					// Store some data in the session
					setcookie('PenclRemember',$_POST['rememberMe']);
					header("Location: noteselection.php");				
					exit;
				}
				else $err[]='Something went wrong on our end! Try again!';
				
				mysql_free_result($giveToken);
			}
			else $err[]='Wrong username and/or password! (1)';
			
			mysql_free_result($result);
		}
		else $err[]='Wrong username and/or password! (2)';
		
		mysql_free_result($userSalt);
	}
	
	if($err)
	$_SESSION['msg']['login-err'] = implode('<br />',$err);
	// Save the error messages in the session

	header("Location: login.php");
	exit;
}
else if($_POST['submit']=='Register')
{
	// If the Register form has been submitted	
	$_SESSION['msg']['formtype'] = 'register';
	
	$err = array();
	
	if(!$_POST['username'] || !$_POST['password'] ||
		$_POST['username'] == 'Username' ||
		$_POST['password'] == 'Password' ||
		$_POST['password'] == 'Confirm Password' ||
		$_POST['betaKey'] == 'Beta Key')
		$err[] = 'All the fields must be filled in!';

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
	if(strcmp($_POST['passwordagain'], $_POST['password']) != 0)
	{
		$err[]='Your passwords do not match!';
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
		
		//$link = mysql_connect($db_host, $db_user, $db_pass) or die('Error connecting to mysql, try again later.');
		//mysql_select_db($db_name);
		
		//$_POST['email'] = mysql_real_escape_string($_POST['email']);
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		$_POST['password'] = mysql_real_escape_string($_POST['password']);
		// Escape the input data
		
		$salt = getSHA();
		$result = mysql_query("	INSERT INTO users(username,password,salt,email,created,ip)
						VALUES(
							'".$_POST['username']."',
							'".getPassword($_POST['username'], $_POST['password'],$salt)."',
							'".$salt."',
							'testAccount@localhost',
							NOW(),
							'".$_SERVER['REMOTE_ADDR']."'							
						)");
		
		//TODO : REMOVE AT PRODUCTION (SECURITY RISK)
		if (!$result) {
		    die('Invalid query (158): ' . mysql_error() . '<br>' . $stringQ);
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
	
	header("Location: login.php");
	exit;
}

$script = '';

if($_SESSION['msg'])
{
	/*
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
	*/
}

?>
