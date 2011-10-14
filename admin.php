<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
?>
<?php
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';
?>

<?php
include_once 'includes/functions.php';

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
function addrow($user, $email, $ip){
global $rownum;
$rownum = $rownum + 1;
return  "<tr>
			<td>$rownum</td>
			<td>$user</td>
			<td><a href='#'>Reset</a></td>
			<td><a href='#'>View Notepads</a></td>
			<td>$email</td>
			<td>Level(ToDo)</td>
			<td>$ip</td>
			<td><a href='#'>Bye Bye</a></td>

		</tr>";
}

?>
<table align="center" border="1" cellpadding="5px">
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
	$result = mysql_query("SELECT * FROM users LIMIT ".$oldlimit.",".($listnumber + 1)."");
	$shownext = false;
	for($i = 0; $i < $listnumber + 1; $i++){
		$row = mysql_fetch_assoc($result);
		if (!$row)
			break;
		if($i != $listnumber){
			echo addrow($row['username'], $row['email'], $row['ip'] );
		}
		else{
			$shownext = true;
		}
	}
?>
</table>
<?php
if ($i == 0)
{
	echo "<p>No entries on this page! <a href=\"?p=1\">Go to page 1</a></p>";
}
?>
<p>Page: <?php echo $pagenumber ?> (
<?php
if ($pagenumber != 1)
	echo "<a href=\"?p=".($pagenumber-1)."\">";
	echo "&lt; Prev";
if ($pagenumber != 1)
	echo "</a>";
	echo " | ";
if ($shownext)
	echo "<a href=\"?p=".($pagenumber+1)."\">";
	echo"Next &gt;";
if ($shownext)
	echo "</a>";
	
function showNumItems($num){
	global $listnumber, $pagenumber;
	if($listnumber != $num){
		echo "<a href=\"?p=".$pagenumber."&n=".$num."\">";
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
)    Show: <?php showNumItems(10);echo" ";showNumItems(25);echo" ";showNumItems(50);echo" ";showNumItems(100);echo" ";showNumItems(200);echo" "; ?></p>



<form class="clearfix" action="" method="post">
	<h3>Change password</h3>
	<div>
	<label class="grey" for="oldpass">Current:</label>
	<input class="field" type="password" name="oldpass" id="password" size="23" />
	</div>
	<div>
	<label class="grey" for="newpass">New:</label>
	<input class="field" type="password" name="newpass" id="password" size="23" />
	</div>
	<div>
	<label class="grey" for="newpass">Retype new:</label>
	<input class="field" type="password" name="newpass2" id="password" size="23" />
	</div>
	<h3>Change Email</h3>
	<div>
	<label class="grey" for="email">Email:</label>
	<input class="field" type="text" name="cemail" id="cemail" value="<?php echo $userRow['email'] ?>" size="23" />
	</div>
	<input type="submit" name="save" value="Save" />
	<input type="submit" name="clearnotes" value="Delete Notebooks" />
</form>

