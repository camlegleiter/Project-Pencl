<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

define('INCLUDE_CHECK', true);

function printAllClasses($userid) {
	$userid = mysql_real_escape_string($userid);
	$padRow1 = mysql_query("SELECT id FROM classes WHERE owner='$userid'");	
	$padRow2 = mysql_query("SELECT classid FROM classmates WHERE userid='$userid'");
	$classDisplay = "";
	
	while ($row = mysql_fetch_assoc($padRow1)) {
		$classDisplay .= getClassRow($row['id']);
	}
	while ($row = mysql_fetch_assoc($padRow2)) {
		$classDisplay .= getClassRow($row['classid']);
	}
	
	mysql_free_result($padRow1);
	mysql_free_result($padRow2);

	if (empty($classDisplay)) {
		$classDisplay = "<tr><td colspan=3>All of the classes you are enrolled in contain this notepad!</td></tr>";
	}
	
	return $classDisplay;
}

function getClassRow($classid) {
	// See if the current notepad ($notepadid) exists within the class ($classid)
	$notepadid = $_GET['notepadid'];
	$padEntry = mysql_query("SELECT * FROM classes c INNER JOIN classbooks cl ON cl.classid = c.id WHERE c.id = '$classid' AND cl.notebookid = '$notepadid';");
	$row = mysql_fetch_assoc($padEntry);
	
	$rowHTML = '';

	// If it doesn't, show that the class can have the notepad added to it
	if (empty($row)) {
		$padRow = mysql_query("SELECT name, description FROM classes WHERE id='$classid'");
		$row = mysql_fetch_assoc($padRow);
		$rowHTML = '
			<tr>
				<td>
				<input type="checkbox" name="share" id="' . $classid . '" value="' . $classid . '">
				</td>
				<td align="left">
					' . $row['name'] . '
				</td>
				<td align="center">
					' . $row['description'] . '
				</td>
			</tr>
					';
		mysql_free_result($padRow);
	}
	
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
					alert("You must select at least 1 notepad, or if none are available select \"Cancel\".");
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
							alert(error.responseText);
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
