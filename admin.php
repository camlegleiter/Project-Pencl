<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
?>
<?php
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

$level = getUserLevel($_SESSION['id']);
if ($level != 0 && $level != 1)
{
	header("Location: noteselection.php");
	exit;
}


if(!empty($_GET['p'])){
	$pagenumber = $_GET['p'];
}
else{
	$pagenumber = 1;
}
if(!empty($_GET['n'])){
	$listnumber = $_GET['n'];
}
else{
	$listnumber = 25;
}

$limit = $pagenumber * $listnumber;
$oldlimit = $limit - $listnumber;
$rownum = $oldlimit;

function adderror($error){
	global $errorarray;
	$errorarray[] = $error;
}

function addsuccess($success){
	global $successarray;
	$successarray[] = $success;
}
//Changes the level of a user
if($_POST['changelevel']){
	$lvl = strtolower($_POST['dropdown']);
	$id = $_POST['id'];
	$intable = false;
	$padCheck = mysql_query("SELECT COUNT(*) FROM admins WHERE userid='$id'");
	$numrows = mysql_fetch_assoc($padCheck);
	mysql_free_result($padCheck);
	if($numrows['COUNT(*)'] != 0)
	{
		$intable = true;
	}
	if(strcmp($lvl, 'lvl1') == 0){
		if($intable == true){
			$update = mysql_query("UPDATE admins SET level = 1 WHERE userid='$id'");
		}
		else{
			$insertPad = mysql_query("INSERT INTO admins (userid, level, created) VALUES ('$id',1,NOW())");
		}
	}
	else if(strcmp($lvl, 'lvl2') == 0){
		if($intable == true){
			$update = mysql_query("UPDATE admins SET level = 2 WHERE userid='$id'");
		}
		else{
			$insertPad = mysql_query("INSERT INTO admins (userid, level, created) VALUES ('$id',2,NOW())");
		}
	}
	else if(strcmp($lvl, 'lvl3') == 0){
		if($intable == true){
			$delete = mysql_query("DELETE FROM admins WHERE userid='$id'");
		}
	}
}

function buildPath($userid){
	//return $url = getcwd().'\\..\\notepads\\'.$userid.'\\'.$notepadid.'\\';
	return $url = getcwd().'/notepads/'.$userid.'/';
}

//Recursive remove directory
function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") 
					rrmdir($dir."/".$object); 
				else 
					unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
		return true;
	}
	return false;
} 

//Deletes a user and all of his/her likeness in the system
if($_GET['delete']){
	$id = $_GET['delete'];
	$delete = mysql_query("DELETE FROM admins WHERE userid='$id'");
	$delete = mysql_query("DELETE FROM classmates WHERE userid='$id'");
	$notepads = mysql_query("SELECT * FROM notebooks WHERE userid='$id'");
	while($row = mysql_fetch_assoc($notepads)){
		$num = $row['id'];
		$delete = mysql_query("DELETE FROM classbooks WHERE notebookid='$num'");
		$delete = mysql_query("DELETE FROM notebooks WHERE id='$num'");
	}
	$delete = mysql_query("DELETE FROM classes WHERE owner='$id'");
	$delete = mysql_query("DELETE FROM users WHERE userid='$id'");
	
	rrmdir(buildPath($id));
}
function addrow($userid, $user, $email, $ip){
	global $rownum;
	$rownum = $rownum + 1;
	$levelnum = getUserLevel($userid);
	$level = getUserLevelStr($levelnum);
	
	return  "<tr>
			<td>$rownum</td>
			<td>$user</td>
			<td><a href='#'>Reset</a></td>
			<td><a href='#'>View Notepads</a></td>
			<td>$email</td>
			<td>
			<form class='clearfix' action='' method='POST' style='width:auto;padding:0;margin-left:0;'>
			<input type='hidden' name='id' value='$userid'>
			<select name='dropdown'>
				<option value='orig' selected>$level</option>
				<option value='disabled' disabled>-------</option>
				<option value='lvl1'>".getUserLevelStr(1)."</option>
				<option value='lvl2'>".getUserLevelStr(2)."</option>
				<option value='lvl3'>".getUserLevelStr(3)."</option>
			</select>
			<input type='submit' value='&gt;' name='changelevel'>
			</form>
			</td>
			<td>$ip</td>
			<td><a href='?delete=$userid' onClick='return removeUser()'>Delete User</a></td>
		</tr>";
}

function showNumItems($num){
	global $listnumber, $pagenumber;
	if($listnumber != $num){
		echo "<a href=\"?p=".$pagenumber."&n=".$num;
		if (isset($_GET['srch']))
			echo "&srch=".$_GET['srch'];
		echo "\">";
	}
	else{
		echo"<strong>";
	}
	echo $num;
	if($listnumber != $num){
		echo "</a>";
	}
	else{
	echo"</strong>";
	}
	
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen">
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
<div id="pagewide">
<h1>Administration Panel</h1>
<form class="clearfix" action="" method="GET">
	<input type="text" name="srch">
	<?php
 		if(!empty($_GET['n'])){
			echo '<input type="hidden" name="n" value="'.$_GET['n'].'">';
		}		
	?>
	<input type="hidden" name="p" value="1">
	<input type="submit" value="Search">
	<a href="<?php echo "?p=".$_GET['p']."&n=".$_GET['n']; ?>">Clear</a>
</form>
<div class="notebook">
<table border="1" cellpadding="5px" style="text-align:center">
	<tr>
		<th>#</th>
		<th>User</th>
		<th>Password</th>
		<th>Notebooks</th>
		<th>Email</th>
		<th>User Level</th>
		<th>Register IP</th>
		<th>Delete</th>
	</tr>
<?php
	$srch = mysql_real_escape_string($_GET['srch']);
	if(isSet($srch)){
		$result = mysql_query("SELECT * FROM users WHERE username LIKE '%".$srch."%' OR email LIKE '%".$srch."%' LIMIT ".$oldlimit.",".($listnumber + 1)."");
	}
	else{
		$result = mysql_query("SELECT * FROM users LIMIT ".$oldlimit.",".($listnumber + 1)."");
	}
	$shownext = false;
	for($i = 0; $i < $listnumber + 1; $i++){
		$row = mysql_fetch_assoc($result);
		if (!$row)
			break;
		if($i != $listnumber){
			echo addrow($row['userid'],$row['username'], $row['email'], $row['ip'] );
		}
		else{
			$shownext = true;
		}
	}
?>
</table>
</div>
<?php
if ($i == 0)
{
	echo "<p>No entries on this page! <a href=\"?p=1\">Go to page 1</a></p>";
}
?>
<p>Page: <?php echo $pagenumber ?> (
<?php
if ($pagenumber != 1) 
{
	echo "<a href=\"?p=".($pagenumber-1)."&n=".$_GET['n'];
	if (isset($_GET['srch']))
		echo "&srch=".$_GET['srch'];
	echo "\">";
}
	echo "&lt; Prev";
if ($pagenumber != 1)
	echo "</a>";
	echo " | ";
if ($shownext)
{
	echo "<a href=\"?p=".($pagenumber+1)."&n=".$_GET['n'];
	if (isset($_GET['srch']))
		echo "&srch=".$_GET['srch'];
	echo "\">";
}
	echo"Next &gt;";
if ($shownext)
	echo "</a>";
	
?>
)    Show: <?php showNumItems(10);echo" ";showNumItems(25);echo" ";showNumItems(50);echo" ";showNumItems(100);echo" ";showNumItems(200);echo" "; ?></p>
</div>

<script type="text/javascript">
function removeUser()
{
	return confirm("Are you sure you want to remove this user?\n\nAll notes and classes (if a teacher) will be removed!");
}
</script>
</body>
</html>
