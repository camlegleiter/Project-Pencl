<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

include 'includes/class_functions.php';

if (isset($_GET['class']) && isset($_GET['id']))
{
	
	if (strcmp(strtolower($_GET['delete']), 'notepad') == 0)
	{
		//Remove notepad from class
		removeNotepadFromClass($_GET['id'], $_GET['class']);
	}
	else if (strcmp(strtolower($_GET['delete']), 'user') == 0)
	{
		removeStudentFromClass($_GET['id'], $_GET['class']);
	}
}

function getClassData($classid)
{
	$arr = array();
	$classid = mysql_real_escape_string($classid);
	$padRow = mysql_query("SELECT name,description,password,owner FROM classes WHERE id='$classid'");
	$row = mysql_fetch_assoc($padRow);

	if ($row)
	{
		$arr['name']        = $row['name'];
		$arr['description'] = $row['description'];
		$arr['password']    = $row['password'];
		$arr['owner']       = $row['owner'];
		$arr['id']          = $classid;
	}
	else
	{
		$arr['name']        = "Error: Couldn't find class name";
		$arr['description'] = "Error: Couldn't find class description";
		$arr['password']    = "";
		$arr['owner']       = "Error: Couldn't find class owner";
		$arr['id']          = $classid;
	}

	mysql_free_result($padRow);
	return $arr;
}

function printAllNotepads($classid)
{
	
	$classid = mysql_real_escape_string($classid);
	$padRow = mysql_query("SELECT notebookid FROM classbooks WHERE classid='$classid'");
	$notepadHTML = "";
	
	while ($row = mysql_fetch_assoc($padRow))
	{
		$notepadHTML = $notepadHTML.getNotepadRow($row['notebookid'],$classid);
	}
	mysql_free_result($padRow);
	
	return $notepadHTML;
}
function printAllStudents($classid)
{
	
	$classid = mysql_real_escape_string($classid);
	$padRow = mysql_query("SELECT userid FROM classmates WHERE classid='$classid'");
	$notepadHTML = "";
	$num = 1;
	
	while ($row = mysql_fetch_assoc($padRow))
	{
		$notepadHTML = $notepadHTML.getStudentRow($row['userid'],$classid,$num);
		$num++;
	}
	mysql_free_result($padRow);
	
	return $notepadHTML;
}


function getNotepadRow($id,$classid)
{
	$id = mysql_real_escape_string($id);
	$padRow = mysql_query("SELECT name,description,created,modified,userid FROM notebooks WHERE id='$id'");
	$row = mysql_fetch_assoc($padRow);
	
	$rowHTML = '';
	
	if($row){
		$rowHTML = '
			<tr>
				<td align="left">
					<a href="canvas.php?id='.$id.'&classid='.$classid.'">'.$row['name'].'</a>
				</td>
				<td align="left">
					'.getUsername($row['userid']).'
				</td>
				<td align="center">
					'.getNiceTime($row['modified']).'
				</td>
				<td align="center">
					'.getNiceTime($row['created']).'
				</td>
				<td align="center">
					<a href="./classes.php?class='.$classid.'&id='.$id.'&delete=notepad" onClick="return confirmRemoveNotepad()">
						<img src="img/buttons/pencl_delete.png" title="Remove from class" alt="Remove">
					</a>
				</td>
			</tr>
					';
	}
	mysql_free_result($padRow);
	return $rowHTML;
}

function getStudentRow($id,$classid,$num)
{
	$id = mysql_real_escape_string($id);
	$padRow = mysql_query("SELECT username FROM users WHERE userid='$id'");
	$row = mysql_fetch_assoc($padRow);
	
	$rowHTML = '';
	
	if($row){
		$rowHTML = '
			<tr>
				<td align="center">
					'.$num.'
				</td>
				<td align="left">
					'.$row['username'].'
				</td>
				<td align="center">
					<a href="./classes.php?class='.$classid.'&id='.$id.'&delete=user" onClick="return confirmRemoveStudent()">
						<img src="img/buttons/pencl_delete.png" title="Remove student from class" alt="Remove">
					</a>
				</td>
			</tr>
					';
	}
	mysql_free_result($padRow);
	return $rowHTML;
}


function getAllClasses()
{
	$userid = mysql_real_escape_string($_SESSION['id']);
	$classRow = mysql_query("SELECT name,description,password,id FROM classes WHERE owner='$userid'");
	$classHTML = "";
	
	while ($row = mysql_fetch_assoc($classRow))
	{
		$classHTML = $classHTML.'
			<tr>
				<td>
					<a href="?class='.$row['id'].'">'.$row['name'].'</a>
				</td>
			</tr>
		';
	}
	mysql_free_result($classRow);
	
	return $classHTML;
}

$class = getClassData($_GET['class']);
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Notepad Selection - Pencl</title>

<!-- Load jQuery -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("jquery", "1");
</script>

<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/twocolumn.css" media="screen">
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
	<div class="twoColumn">
		<div class="left">
			<table>
				<thead>
					<tr class="head">
						<td>
							<strong>Classes:</strong>
						</td>
					</tr>
				</thead>
				<tbody>
					<?php
						//Grab our classes
						echo getAllClasses();
					?>
				</tbody>
			</table>
			<br>
			<a href="createClass.php">Create a class</a>
		</div>
		<div class="right">
			<?php 
			//Display table only if we are displaying a class
			//Start display
			if (is_numeric($_GET['class'])) {
				echo '<h1>Class: <strong>'.$class['name'].'</strong> ';
				echo '<a href="editClass.php?classid='.$class['id'].'">(Edit)</a>';
				echo '<a href= "#" onclick="deleteClass();"> (Delete Class)</a>';
				
				if (strlen($class['password']) > 0)
				{
					echo '<img src="img/buttons/pencl_lock.png" title="Password Protected" alt="(Password Protected)">';
				}
				echo '</h1>';
				echo '<p>Description: '.$class['description'].'</p>';
			?>
				<br>
				<div class="notebook">
					<table>
						<thead>
							<tr class="head">
								<td>
									<strong>Notepad</strong>
								</td>
								<td>
									<strong>Creator</strong>
								</td>
								<td>
									<strong>Modified</strong>
								</td>
								<td>
									<strong>Created</strong>
								</td>
								<td>
									<strong>Options</strong>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php
								//Grab our notepads
								echo printAllNotepads($_GET['class']);
							?>
						</tbody>
					</table>
				</div>
				<br>
				<p>Tip: To add notebooks to this class, share them from your <a href="noteselection.php">notes</a></p>
				<br>
				<h1>Students enrolled:</h1>
				<div class="notebook">
					<table>
						<thead>
							<tr class="head">
								<td>
									<strong>#</strong>
								</td>
								<td>
									<strong>Username</strong>
								</td>
								<td>
									<strong>Options</strong>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php
								//Grab our students
								echo printAllStudents($_GET['class']);
							?>
						</tbody>
					</table>
				</div>
			<?php
			//End display
			}
			else
			{
			?>
				<p>Select a class on the left, or <a href="createClass.php">create a class</a>.</p>
			<?php
			}
			?>
		</div>
	</div>
</div>
<script type="text/javascript">

function confirmRemoveStudent()
{
	return confirm('Are you sure you want to remove this student?\nAll notepads linking to this student will also be removed!');
}
function confirmRemoveNotepad()
{
	return confirm('Are you sure you want to remove this notepad?');
}
function confirmRemoveClass()
{
	return confirm('Are you sure you want to Delete?');
}

function deleteClass()
{
	var querystring = location.search.replace('?', '').split('&');
	var queryObj ={};
	for (var i = 0; i < querystring.length; i++) {
				var name = querystring[i].split('=')[0];
				var value = querystring[i].split('=')[1];

				queryObj[name] = value;
			}
			
	$.ajax({
					type: 'POST',
					url: './util/classPost.php',
					data: {
						action: 'delete',
						classid: parseInt(queryObj['class'])
					},
					statusCode: {
						404: function() {
							alert('Page not found!');
							// Hide progress
							//tinymce.get('elm1').setProgressState(0);
						},
						409: function(jqXHR, status, error) {
							alert('Error: ' + error);
							// Hide progress
							//tinymce.get('elm1').setProgressState(0);
						},
						200: function(data) {
							alert(data);
							// Hide progress
							//window.setTimeout(function() {tinymce.get('elm1').setProgressState(0)}, 500);
						}
					}
					
				});
				
	confirmRemoveClass();
	var url = window.location.href;
	var newUrl = url.split('?');
	window.location.href = newUrl[0];
}
</script>

</body>
</html>
