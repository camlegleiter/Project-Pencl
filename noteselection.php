<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

function printAllNotepads($userid)
{
	$userid = mysql_real_escape_string($userid);
	//$padRow = mysql_query("SELECT id FROM notepads WHERE userid='$userid'");
	$notepadHTML = "SELECT id FROM notebooks WHERE userid='$userid'";
	/*
	while ($row = mysql_fetch_assoc($padRow))
	{
		$notepadHTML = $notepadHTML.getNotepadRow($userid, $row['id']);
	}
	mysql_free_result($padRow);
	*/
	return $notepadHTML;
}

function getNotepadRow($userid, $id)
{
	$padRow = mysql_query("SELECT name,description,created,modified FROM notebooks WHERE userid='$userid' AND id='$id'");
	$row = mysql_fetch_assoc($padRow);
	
	$rowHTML = '';
	
	if($row){
		$rowHTML = '
			<tr>
				<td>
					'.$row['name'].'
				</td>
				<td>
					'.$row['modified'].'
				</td>
				<td>
					'.$row['created'].'
				</td>
				<td>
					<a href="#">Delete</a>
				</td>
			</tr>
					';
	}
	mysql_free_result($padRow);
	return $rowHTML;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">
</head>

<body>
<?php
//Must be first thing in the <body> tag to function correctly
include 'includes/headerbar.php';
?>


<div id="page">
	<div class="notebook">
		<table>
			<thead>
				<tr class="head">
					<td>
						<strong>Notepad</strong></td>
					<td>
						<strong>Modified</strong></td>
					<td>
						<strong>Created</strong></td>
					<td>
						<strong>Delete</strong></td>
				</tr>
			</thead>
			<tbody>
				<?php
					//Grab our notepads
					echo printAllNotepads($_SESSION['id']);
				?>
			</tbody>
		</table>
	</div>
</div>
</body>
</html>
