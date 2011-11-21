<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';

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
					'.$row['modified'].'
				</td>
				<td align="center">
					'.$row['created'].'
				</td>
				<td align="center">
					<a href="#edit" onClick="renameNotepad('.$id.', false)">
						<img src="img/buttons/pencl_edit.png" title="Edit" alt="Edit">
					</a>
					<a href="share.php">
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
