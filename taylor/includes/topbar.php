<?php 
//session_name('CyCalLogin');

//session_start(); 
?>

<div class="tb" id="topContainer">
	<div class="chromestyle" id="chromemenu">
		<div class="tb" id="topLogo"></div>
		<ul>
			<li><a href="../../../CyCal/includes/help.php">Help</a></li>
			<li><a href="#" rel="dropmenu1">Hello, <?php echo $_SESSION['usr']; ?></a></li>
		</ul>
	</div>
</div>

<!--1st drop down menu -->                                                   
<span id="dropmenu1" class="dropmenudiv">
<?php
if (!defined("SETTINGSPAGE"))
{
	echo '
	<a href="#addISU" onclick="addISUFeed()">Add ISU Feed</a>
	<a href="#addOther" onclick="addOtherFeed()">Add Other Feed</a>
	<a href="settings.php">Account Settings</a>
	';
}
else
{
	echo '
	<a href="canvas.php">Back to Canvas</a>
	';
}
?>
	<a href="../../../CyCal/includes/logout.php">Log Out</a>
</span>

<script type="text/javascript">

	cssdropdown.startchrome("chromemenu")

</script>