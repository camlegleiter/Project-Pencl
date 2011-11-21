<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

function printAllClasses($userid)
{
	$userid = mysql_real_escape_string($userid);
	$padRow = mysql_query("SELECT classid FROM classmates WHERE userid='$userid'");
	$padRow2 = mysql_query("SELECT owner FROM classes WHERE id='$userid'");
	$classmatesHTML = "";
	
	while ($row = mysql_fetch_assoc($padRow2))
	{
		$classmatesHTML .= getClassRow($userid, $row['owner']);
	}
	while ($row = mysql_fetch_assoc($padRow))
	{
		$classmatesHTML .= getClassRow($userid, $row['classid']);
	}
	
	mysql_free_result($padRow);
	mysql_free_result($padRow2);
	
	return $classmatesHTML;
}

function getClassRow($userid, $classid)
{
	$padRow = mysql_query("SELECT name, description FROM classes WHERE id='$classid'");
	$row = mysql_fetch_assoc($padRow);
	
	$rowHTML = '';
	
	if($row)
	{
		$rowHTML = '
			<tr>
				<td>
				<input type="checkbox" name="share" value="Share" /> Share
				</td>
				<td align="left">
					<a href="canvas.php?id='.$classid.'">'.$row['name'].'</a>
				</td>
				<td align="center">
					'.$row['description'].'
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
<title>Notepad Selection - Pencl</title>

<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/dialog/jqModal.css">

<script type="text/javascript" src="js/dialog/jqModal.js"></script>
<?php
//Must be in header!
include 'includes/topbar_header.php';
?>

<?php
//Put this at the end of the <head> tag to track
include 'includes/tracker.php';
?>
</head>

<body>
<?php
//Must be first thing in the <body> tag to function correctly
include 'includes/topbar.php';
?>
<div id="pagewide">
	<h1>Which classes would you like to share with?</h1>
	
	<div class="notebook">
		<table>
			<thead>
				<tr class="head">
					<td>
						<!-- Preview -->
                        <strong>Share</strong>
                        
					</td>
					<td>
						<strong>Classes</strong>
					</td>
					<td>
						<strong>Description</strong>
					</td>
				</tr>
			</thead>
			<tbody>
				<?php
					//Grab our notepads
					echo printAllClasses($_SESSION['id']);
				?>
                
			</tbody>
		</table>
	</div>
	<br>
	<p>Tip: Choose other options in the drop down menu at the top-right!</p>
</div>
</body>
</html>