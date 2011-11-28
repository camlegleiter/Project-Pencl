<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

include 'includes/class_functions.php';

if ($_GET['leave'])
{
	if (is_numeric($_GET['leave']))
	{
		if (removeStudentFromClass($_SESSION['id'], $_GET['leave']))
			$_SESSION['msg']['success'] = 'You have left the class.';
		else
			$_SESSION['msg']['err'] = 'You are not enrolled in this class.';
	}
}

function printAllNotepads($userid)
{
	$userid = mysql_real_escape_string($userid);
	$padRow = mysql_query("SELECT id FROM notebooks WHERE userid='$userid'");
	$notepadHTML = "";
	
	while ($row = mysql_fetch_assoc($padRow))
	{
		$notepadHTML = $notepadHTML.getNotepadRow($userid, $row['id']);
	}
	mysql_free_result($padRow);
	
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
					<a href="#preview">O</a>
				</td>
				<td align="left">
					<a href="canvas.php?id='.$id.'">'.$row['name'].'</a>
				</td>
				<td align="center">
					'.getNiceTime($row['modified']).'
				</td>
				<td align="center">
					'.getNiceTime($row['created']).'
				</td>
				<td align="center">
					<a href="#edit" onClick="renameNotepad('.$id.', false)">
						<img src="img/buttons/pencl_edit.png" title="Edit" alt="Edit">
					</a>
					<a href="share.php?notepadid='.$id.'">
						<img src="img/buttons/pencl_share.png" title="Share" alt="Share">
					</a>
					<a href="export.php?notepadid='.$id.'">
						<img src="img/buttons/pencl_export.png" title="Export" alt="Export">
					</a>
					<a href="#delete" onClick="deleteNotepad('.$id.')">
						<img src="img/buttons/pencl_delete.png" title="Delete" alt="Delete">
					</a>
				</td>
			</tr>
					';
	}
	mysql_free_result($padRow);
	return $rowHTML;
}

function printAllClassNotepads($userid)
{
	$userid = mysql_real_escape_string($userid);
	
	//Print ones they teach (Won't do anything if they are a student...
	$padRow = mysql_query("SELECT id FROM classes WHERE owner='$userid'");
	$classHTML = "";
	
	while ($row = mysql_fetch_assoc($padRow))
	{
		$classHTML = $classHTML.printClassSection($userid, $row['id']);
	}
	mysql_free_result($padRow);

	//Print all enrolled courses
	$padRow = mysql_query("SELECT classid FROM classmates WHERE userid='$userid'");
	
	while ($row = mysql_fetch_assoc($padRow))
	{
		$classHTML = $classHTML.printClassSection($userid, $row['classid']);
	}
	mysql_free_result($padRow);
	
	return $classHTML;
}

function printClassSection($userid,$classid)
{
	$classHTML = '';
	
	$classid = mysql_real_escape_string($classid);
	$classRow = mysql_query("SELECT name,description,owner,password FROM classes WHERE id='$classid'");
	$notepadHTML = "";
	
	$row = mysql_fetch_assoc($classRow);
	if ($row) {
		$ownerHTML = '';
		//Check to see if they teach this class
		if ($row['owner'] == $userid)
		{
			$classHTML = '<h2>'.$row['name'].' (<a href="classes.php?class='.$classid.'">Manage</a>)</h2>';
		}
		else
		{
			$classHTML = '<h2>'.$row['name'].' (<a href="?leave='.$classid.'" onClick="return leaveClass()">Leave</a>)</h2>';
		}
		
		$classHTML = $classHTML.'
			<h3>'.$row['description'].'</h2>
			<div class="notebook">
				<table>
					<thead>
						<tr class="head">
							<td>
								<!-- Preview -->
							</td>
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
						'.getAllNotepadsFromClass($classid).'
					</tbody>
				</table>
			</div>
			<br>
		';
		
	}
	mysql_free_result($classRow);
	
	return $classHTML;

}

function getAllNotepadsFromClass($classid)
{
	$padRow = mysql_query("SELECT notebookid FROM classbooks WHERE classid='$classid'");
	$classHTML = '';

	while ($row = mysql_fetch_assoc($padRow))
	{
		$notepadHTML = printNotepad($row['notebookid'],$classid);
		$classHTML = $classHTML.$notepadHTML;
	}
	mysql_free_result($padRow);
	
	return $classHTML;
}

function printNotepad($id,$classid)
{
	$id = mysql_real_escape_string($id);
	$padRow = mysql_query("SELECT name,description,created,modified FROM notebooks WHERE id='$id'");
	$row = mysql_fetch_assoc($padRow);
	
	$rowHTML = '';
	
	if($row){
		$rowHTML = '
			<tr>
				<td>
					<a href="#preview">O</a>
				</td>
				<td align="left">
					<a href="canvas.php?id='.$id.'&classid='.$classid.'">'.$row['name'].'</a>
				</td>
				<td align="center">
					'.getNiceTime($row['modified']).'
				</td>
				<td align="center">
					'.getNiceTime($row['created']).'
				</td>
				<td align="center">
					<a href="export.php?notepadid='.$id.'">
						<img src="img/buttons/pencl_export.png" title="Export" alt="Export">
					</a>
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

<!-- Load jQuery -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("jquery", "1");
</script>

<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen">
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
	<?php 
		if ($_SESSION['msg']['err'])
		{
			echo '<h1 class="error">'.$_SESSION['msg']['err'].'</h1>';
			unset($_SESSION['msg']['err']);
		}
		if ($_SESSION['msg']['success'])
		{
			echo '<h1 class="success">'.$_SESSION['msg']['success'].'</h1>';
			unset($_SESSION['msg']['success']);
		}
	?>
	<h1>Which notepad would you like to open?</h1>
	<div id="page_header">
		<p><a href="#new" onclick="newNotepad()"><img src="img/buttons/pencl_new_large.png" title="New Notepad" alt="New Notepad"></a></p>
	</div>
	<div class="notebook">
		<table>
			<thead>
				<tr class="head">
					<td>
						<!-- Preview -->
					</td>
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
					echo printAllNotepads($_SESSION['id']);
				?>
			</tbody>
		</table>
	</div>
	<br>
	<p>Tip: Choose other options in the drop down menu at the top-right!</p>
	<br>
	<h1>Class Notepads:</h1>
	<?php 
		echo printAllClassNotepads($_SESSION['id']);
	?>	
</div>
<div class="popup" id="newPopup" style="display:none">
	<div class="header">
		<h1>New Notepad</h1>
		<div id="buttons">
			<a id="close"><img src="img/buttons/popup_close.png" title="Close" alt="X"></a>
		</div>
	</div>
	<div class="content">
			<p>Name:</p>
			<input type="text" name="Name" id="NotepadName" size="30" maxlength="30"><br>
			<p>Description:</p>
			<textarea cols="45" rows="5" name="Description" id="NotepadDesc" maxlength="256"></textarea><br>
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
			<p>Name:</p>
			<input type="text" name="Name" id="NotepadName" size="30" maxlength="30"><br>
			<p>Description:</p>
			<textarea cols="45" rows="5" name="Description" id="NotepadDesc" maxlength="256"></textarea><br>
		<p>When you create this notepad, you will be directed right to the editting page.</p>
	</div>
	<div class="footer">
		<a href="#" id="save">Save</a>
	</div>
</div>

<script type="text/javascript">
	function leaveClass()
	{
		return confirm('Are you sure you want to leave this class?');
	}

	$('#newPopup').jqm();
	$('#newPopup').jqmAddClose($('#newPopup .header #buttons #close'));
	$('#editPopup').jqm();
	$('#editPopup').jqmAddClose($('#editPopup .header #buttons #close'));
	function newNotepad()
	{
		$('#newPopup .content #NotepadName').val('');
		$('#newPopup .content #NotepadDesc').val('');
		$('#newPopup').jqmShow();
		$('#newPopup .content #NotepadName').focus();
	}
	function createNotepad()
	{
		$.ajax({
			type: 'POST',
			url: './util/notepadPost.php',
			data: {
				action: 'create',
				notepadname: $('#newPopup .content #NotepadName').val(),
				notepaddesc: $('#newPopup .content #NotepadDesc').val()
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
						$('#editPopup .content #NotepadName').val(data.notepadname);
						$('#editPopup .content #NotepadDesc').val(data.notepaddesc);
						//Clear click events
						$('#editPopup .footer #save').unbind('click');
						//Set new click event
						$('#editPopup .footer #save').click(function() {renameNotepad(id,true)});
						$('#editPopup').jqmShow();
						$('#editPopup .content #NotepadName').focus();
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
					notepadname: $('#editPopup .content #NotepadName').val(),
					notepaddesc: $('#editPopup .content #NotepadDesc').val()
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
