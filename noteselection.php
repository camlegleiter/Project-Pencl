<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
?>
<?php
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';
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
				?>
				<tr>
					<td>
						Notepad #1</td>
					<td>
						11/3/2011</td>
					<td>
						11/3/2011</td>
					<td>
						X</td>
				</tr>
				<tr class="odd"> 
					<td>
						Notepad #2</td>
					<td>
						11/3/2011</td>
					<td>
						11/3/2011</td>
					<td>
						X</td>
				</tr>
				<tr>
					<td>
						Notepad #3</td>
					<td>
						11/3/2011</td>
					<td>
						11/3/2011</td>
					<td>
						X</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</body>
</html>
