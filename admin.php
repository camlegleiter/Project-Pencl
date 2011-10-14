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

function adderror($error){
	global $errorarray;
	$errorarray[] = $error;
}

function addsuccess($success){
	global $successarray;
	$successarray[] = $success;
}
function addrow($id, $user, $email){
return  "<tr>
			<td>$id</td>
			<td>$user</td>
			<td><a href='#'>Reset Password</a></td>
			<td><a href='#'>View Notepads</a></td>
			<td>$email</td>
		</tr>";
}

?>
<table align="center" border="1" cellpadding="5px">
	<tr>
		<th>ID</th>
		<th>User</th>
		<th>Password</th>
		<th>Notebooks</th>
		<th>Email</th>
		<th>User Level</th>
		<th>Delete</th>
	</tr>
<?php
	$result = mysql_query("SELECT * FROM users");
	$row = mysql_fetch_assoc($result);
	echo addrow($row['userid'],$row['username'], $row['email'] );
	
?>
</table>






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

