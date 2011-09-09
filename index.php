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

$msg = '';

if($_POST['email']){
	
	// Requested with AJAX:
	$ajax = ($_SERVER['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest');
	
	try{
		if(!filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)){
			throw new Exception('Invalid Email!');
		}

		$mysqli->query("INSERT INTO coming_soon_emails
						SET email='".$mysqli->real_escape_string($_POST['email'])."'");
		
		if($mysqli->affected_rows != 1){
			throw new Exception('This email already exists in the database.');
		}
		
		if($ajax){
			die('{"status":1}');
		}
		
		$msg = "Thank you!";
		
	}
	catch (Exception $e){
		
		if($ajax){
			die(json_encode(array('error'=>$e->getMessage())));
		}
		
		$msg = $e->getMessage();		
	}
}

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
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		$_POST['password'] = mysql_real_escape_string($_POST['password']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		
		// Escaping all input data

		$row = mysql_fetch_assoc(mysql_query("SELECT id,usr FROM tz_members WHERE usr='{$_POST['username']}' AND pass='".md5($_POST['password'])."'"));

		if($row['usr'])
		{
			// If everything is OK login
			
			$_SESSION['usr']=$row['usr'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['rememberMe'] = $_POST['rememberMe'];
			
			// Store some data in the session
			
			setcookie('tzRemember',$_POST['rememberMe']);
		}
		else $err[]='Wrong username and/or password!';
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
	
	if(!checkEmail($_POST['email']))
	{
		$err[]='Your email is not valid!';
	}
	
	if(!count($err))
	{
		// If there are no errors
		
		$pass = substr(md5($_SERVER['REMOTE_ADDR'].microtime().rand(1,100000)),0,6);
		// Generate a random password
		
		$_POST['email'] = mysql_real_escape_string($_POST['email']);
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		// Escape the input data
		mysql_query("	INSERT INTO tz_members(usr,pass,email,regIP,dt)
					VALUES(
						
							'".$_POST['username']."',
							'".md5($pass)."',
							'".$_POST['email']."',
							'".$_SERVER['REMOTE_ADDR']."',
							NOW()
							
						)");
		
		if(mysql_affected_rows($link)==1)
		{
			send_mail(	'demo-test@tutorialzine.com',
						$_POST['email'],
						'Registration System Demo - Your New Password',
						'Your password is: '.$pass);

			$_SESSION['msg']['reg-success']='We sent you an email with your new password!';
		}
		else $err[]='This username is already taken!';
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
	// The script below shows the sliding panel on page load
	
	$script = '
	<script type="text/javascript">
	
		$(function(){
		
			$("div#panel").show();
			$("#toggle a").toggle();
		});
	
	</script>';
	
}

?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/nivo-slider.css" />
<link rel="stylesheet" type="text/css" href="css/index.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/slide.css" media="screen" />


	<!-- PNG FIX for IE6 -->
    <!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
    <!--[if lte IE 6]>
        <script type="text/javascript" src="js/pngfix/supersleight-min.js"></script>
    <![endif]-->
    
    <!-- Javascript (jQuery) -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
    
    <?php echo $script; ?>


</head>

<body>
<!-- Panel -->
<div id="toppanel">
	<div id="panel">
		<div class="content clearfix">
			<div class="left">
				<h1>Pencl</h1>
				<h2>A web-base note taking application.</h2>		
				<p class="grey">Collaborate, share, and create!</p>
				<h2>Open Beta Soon!</h2>
				<p class="grey">Enter your email address below to hear when we open it up for test phases - you could be one of them! Sign up now before it's too late!</p>
			</div>
            
            
            <?php
			
			if(!$_SESSION['id']):
			
			?>
            
			<div class="left">
				<!-- Nothing here! Yet! -->
				<h1></h1>
			</div>
			<div class="left right">			
				<!-- Login Form -->
				<form class="clearfix" action="" method="post">
					<h1>Member Login</h1>
                    
                    <?php
						
						if($_SESSION['msg']['login-err'])
						{
							echo '<div class="err">'.$_SESSION['msg']['login-err'].'</div>';
							unset($_SESSION['msg']['login-err']);
						}
					?>
					
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="password">Password:</label>
					<input class="field" type="password" name="password" id="password" size="23" />
	            	<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Login" class="bt_login" />
				</form>
			</div>
            
            <?php
			
			else:
			
			?>
            
            <div class="left">
            
            <h1>Members panel</h1>
            
            <p>You can put member-only data here</p>
            <a href="registered.php">View a special member page</a>
            <p>- or -</p>
            <a href="?logoff">Log off</a>
            
            </div>
            
            <div class="left right">
            </div>
            
            <?php
			endif;
			?>
		</div>
	</div> <!-- /login -->	

    <!-- The tab on top -->	
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
	        <li>Hello <?php echo $_SESSION['usr'] ? $_SESSION['usr'] : 'Guest';?>!</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open" href="#"><?php echo $_SESSION['id']?'Open Panel':'Log In | <del>Register</del>';?></a>
				<a id="close" style="display: none;" class="close" href="#">Close Panel</a>		
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> <!-- / top -->
	
</div> <!--panel -->

<div id="page">

    <h1>Coming Soon</h1>
    
    <div id="slideshowContainer">
        <div id="slideshow">
            <img src="img/slides/slide1.jpg" width="454" height="169" alt="Coming Soon: Our Awesome Web App">
            <img src="img/slides/slide2.jpg" width="454" height="169" alt="Extensive Functionality">
        </div>
	</div>
        
    <h2>Subscribe</h2>
    
    <form method="post" action="index.php">
    	<input type="text" id="email" name="email" value="<?php echo $msg?>"></input>
        <input type="submit" value="Submit" id="submitButton"></input>
    </form>
    
</div>

<!-- Feel free to remove this footer -->

<div id="footer">
	<div class="tri"></div>
	<h1>Pencl - The web-based note taking application.</h1>
</div>

	<!-- Javascript (Called after jQuery) -->
	<script type="text/javascript" src="js/jquery.nivo.slider.pack.js"></script>
    <script type="text/javascript" src="js/slide.js"></script>
	<script type="text/javascript" src="js/script.js"></script>


</body>
</html>
