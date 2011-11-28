<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

define('INCLUDE_CHECK', true);

function printAllClasses($userid)
{
	$userid = mysql_real_escape_string($userid);
	$padRow = mysql_query("SELECT classid FROM classmates WHERE userid='$userid'");
	$padRow2 = mysql_query("SELECT id FROM classes WHERE owner='$userid'");
	$classmatesHTML = "";
	
	while ($row = mysql_fetch_assoc($padRow2))
	{
		$classmatesHTML .= getClassRow($userid, $row['id']);
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
				<input type="checkbox" name="share" value="' . $classid . '">
				</td>
				<td align="left">
					'.$row['name'].'
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
		<script type="text/javascript" src="js/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
			function validateForm() {
				var notepads = new Array();
				var querystring = location.search.replace('?', '').split('&');
				var queryObj = {};

				// Get the URL querystring values
				for (var i = 0; i < querystring.length; i++) {
					var name = querystring[i].split('=')[0];
					var value = querystring[i].split('=')[1];

					queryObj[name] = value;
				}

				// Get checkbox elements
				for (var i = 0; i < document.forms[0].elements.length; i++ ) {
					if (document.forms[0].elements[i].type == 'checkbox') {
						if (document.forms[0].elements[i].checked == true) {
							notepads.push(document.forms[0].elements[i].value);
						}
					}
				}
				if (notepads.length < 1) {
					alert("");
					return false;
				}

				var jsonString = JSON.stringify(notepads);

				$.ajax({
					type: "POST",
					url: "util/shareClasses.php",
					data: {
						notepadid: parseInt(queryObj['notepadid']),
						classes: jsonString
					},
					statusCode: {
						200: function() {
							window.location = "noteselection.php";
						},
						409: function(error) {
							alert("Could not share notepads with class - server error: " + error.responseText);
						}
					}
				});
			}
		</script>
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
				<form name="shareForm" method="POST" action="javascript:validateForm()">
					<table>
						<thead>
							<tr class="head">
								<td>
									<!-- Preview -->
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
					<input type="submit" value="Submit">
					<input type="button" value="Cancel" onclick="javascript:window.location='noteselection.php'">
				</form>
			</div>
			<br>
			<p>Tip: Choose other options in the drop down menu at the top-right!</p>
		</div>
	</body>
</html>
