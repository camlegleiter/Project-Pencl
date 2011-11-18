<?php
define('INCLUDE_CHECK',true);

// Those files can be included only if INCLUDE_CHECK is defined
require_once 'includes/functions.php';
?>

<div class="tb" id="topContainer">
	<div class="chromestyle" id="chromemenu">
		<div class="tb" id="topLogo"></div>
		<ul>
			<!--<li><a href="../../../CyCal/includes/help.php">Help</a></li>-->
			<li><?php
			echo '<a href="'; 
			if(defined("LOGGEDIN")) {
				echo '#';
			}
			else {
				echo 'login.php';
			}
			echo '" rel="dropmenu1">';
			if(defined("LOGGEDIN")) {
				echo 'Hello, '.$_SESSION['usr'];
			}
			else {
				echo 'Login';
			}
			echo '</a>';
			?></li>
		</ul>
	</div>
</div>

<!--1st drop down menu -->                                                   
<span id="dropmenu1" class="dropmenudiv">
<?php

if (!defined("LOGGEDIN")) {
	echo'
	<a href="register.php">Register</a>
	';
}
else if (defined("CANVAS"))
{
	echo '
	<a href="noteselection.php">Notepad Selection</a>
	<a href="#Save" onclick="save()">Save</a>
	<a href="logout.php">Logout</a>
	';
}
else
{
	echo '
	<a href="settings.php">Settings</a>
	<a href="logout.php">Logout</a>
	';
}

$userLevel = getUserLevel($_SESSION['id']);

if($userLevel == '0' | $userLevel == '1'){
	echo'
	<a href="admin.php">Admin</a>
	';
}
?>
</span>

<script type="text/javascript">

	cssdropdown.startchrome("chromemenu")

</script>