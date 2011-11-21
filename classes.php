<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

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
	}
	else
	{
		$arr['name']        = "Error: Couldn't find class name";
		$arr['description'] = "Error: Couldn't find class description";
		$arr['password']    = "";
		$arr['owner']       = "Error: Couldn't find class owner";
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

function getNotepadRow($id,$classid)
{
	$id = mysql_real_escape_string($id);
	$padRow = mysql_query("SELECT name,description,created,modified FROM notebooks WHERE id='$id'");
	$row = mysql_fetch_assoc($padRow);
	
	$rowHTML = '';
	
	if($row){
		$rowHTML = '
			<tr>
				<td align="left">
					<a href="canvas.php?id='.$id.'&classid='.$classid.'">'.$row['name'].'</a>
				</td>
				<td align="center">
					'.$row['modified'].'
				</td>
				<td align="center">
					'.$row['created'].'
				</td>
				<td align="center">
					<a href="#export" onClick="alert(\'Coming soon!\')">
						<img src="img/buttons/pencl_export.png" title="Export" alt="Export">
					</a>
					<a href="#delete" onClick="alert(\'Coming soon!\')">
						<img src="img/buttons/pencl_delete.png" title="Delete" alt="Delete">
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
			?>
				<h1>Class: <strong><?php echo $class['name'] ?></strong></h1>
				<div class="notebook">
					<table>
						<thead>
							<tr class="head">
								<td>
									<strong>Notepad</strong>
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
<div class="popup" id="newPopup" style="display:none">
	<div class="header">
		<h1>New Notepad</h1>
		<div id="buttons">
			<a id="close"><img src="img/buttons/popup_close.png" title="Close" alt="X"></a>
		</div>
	</div>
	<div class="content">
		<form>
			<p>Name:</p>
			<input type="text" name="Name" id="NotepadName" size="30" maxlength="30"><br>
			<p>Description:</p>
			<textarea cols="45" rows="5" name="Description" id="NotepadDesc" maxlength="256"></textarea><br>
		</form>
		<p>When you create this notepad, you will be directed right to the editting page.</p>
	</div>
	<div class="footer">
		<a href="#" onclick="createNotepad()">Create!</a>
	</div>
</div>
<div class="popup" id="editPopup" style="display:none">
	<div class="header">
		<h1>Edit Notepad</h1>
		<div id="buttons">
			<a id="close"><img src="img/buttons/popup_close.png" title="Close" alt="X"></a>
		</div>
	</div>
	<div class="content">
		<form>
			<p>Name:</p>
			<input type="text" name="Name" id="NotepadName" size="30" maxlength="30"><br>
			<p>Description:</p>
			<textarea cols="45" rows="5" name="Description" id="NotepadDesc" maxlength="256"></textarea><br>
		</form>
		<p>When you create this notepad, you will be directed right to the editting page.</p>
	</div>
	<div class="footer">
		<a href="#" id="save">Save</a>
	</div>
</div>

<script type="text/javascript">
	$('#newPopup').jqm();
	$('#newPopup').jqmAddClose($('#newPopup .header #buttons #close'));
	$('#editPopup').jqm();
	$('#editPopup').jqmAddClose($('#editPopup .header #buttons #close'));
	function newNotepad()
	{
		$('#newPopup .content form #NotepadName').val('');
		$('#newPopup .content form #NotepadDesc').val('');
		$('#newPopup').jqmShow();
		$('#newPopup .content form #NotepadName').focus();
	}
	function createNotepad()
	{
		$.ajax({
			type: 'POST',
			url: './util/notepadPost.php',
			data: {
				action: 'create',
				notepadname: $('#newPopup .content form #NotepadName').val(),
				notepaddesc: $('#newPopup .content form #NotepadDesc').val()
			},
			dataType: "json",
			statusCode: {
				404: function() {
					alert('Page not found!');
				},
				409: function(jqXHR, status, error) {
					alert('Error: ' + error);
				},
				200: function(data) {
					//Redirect to canvas
					window.location = "./canvas.php?id="+data.notepadid;
				}
			}
		});
	}
	function renameNotepad(id, save)
	{
		if (!save)
		{
			//Fetch our data
			$.ajax({
				type: 'POST',
				url: './util/notepadPost.php',
				data: {
					action: 'getrename',
					notepadid: id
				},
				dataType: "json",
				statusCode: {
					404: function() {
						alert('Page not found!');
					},
					409: function(jqXHR, status, error) {
						alert('Error: ' + error);
					},
					200: function(data) {
						$('#editPopup .content form #NotepadName').val(data.notepadname);
						$('#editPopup .content form #NotepadDesc').val(data.notepaddesc);
						//Clear click events
						$('#editPopup .footer #save').unbind('click');
						//Set new click event
						$('#editPopup .footer #save').click(function() {renameNotepad(id,true)});
						$('#editPopup').jqmShow();
						$('#editPopup .content form #NotepadName').focus();
					}
				}
			});
		}
		else
		{
			//Save our data
			$.ajax({
				type: 'POST',
				url: './util/notepadPost.php',
				data: {
					action: 'rename',
					notepadid: id,
					notepadname: $('#editPopup .content form #NotepadName').val(),
					notepaddesc: $('#editPopup .content form #NotepadDesc').val()
				},
				dataType: "json",
				statusCode: {
					404: function() {
						alert('Page not found!');
					},
					409: function(jqXHR, status, error) {
						alert('Error: ' + error);
					},
					200: function(data) {
						alert('Saved!');
						$('#editPopup').jqmHide();
						window.location.reload();
					}
				}
			});
		}
	}
	function deleteNotepad(id)
	{
		if (confirm("Do really want to delete this notepad?"))
		{
			$.ajax({
				type: 'POST',
				url: './util/notepadPost.php',
				data: {
					action: 'delete',
					notepadid: id
				},
				statusCode: {
					404: function() {
						alert('Page not found!');
					},
					409: function(jqXHR, status, error) {
						alert('Error: ' + error);
					},
					200: function(data) {
						//Reload page
						window.location.reload();
					}
				}
			});
		}
	}
</script>
</body>
</html>
