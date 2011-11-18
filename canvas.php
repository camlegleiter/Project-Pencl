<?php
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Canvas - Pencl</title>
		<link rel="stylesheet" type="text/css" href="css/canvas.css">
		
		<!-- Load jQuery -->
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<script type="text/javascript">
			google.load("jquery", "1");
		</script>
			
		<!-- Load TinyMCE -->
		<script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>
		<script type="text/javascript">
			var querystring = location.search.replace('?', '').split('&');
			var queryObj = {};
			var currentSave = "";

			// Get the URL querystring values
			for (var i = 0; i < querystring.length; i++) {
				var name = querystring[i].split('=')[0];
				var value = querystring[i].split('=')[1];

				queryObj[name] = value;
			}

			// Runs every 10 seconds to save the editor content
			function autosave() {
				save();
				setTimeout("autosave()", 10000);				
			}

			// Modifies the current content value
			function save() {
				var editorText = tinymce.get('elm1').getContent();

				if (currentSave != editorText) {
					currentSave = editorText;
				}
			}

			// Save content from editor into the file
			function writeToFile() {
				save();
				$.ajax({
					type: 'POST',
					url: './util/notepadPost.php',
					data: {
						action: 'save',
						notepadid: parseInt(queryObj['id']),
						content: currentSave
					},
					statusCode: {
						404: function() {
							alert('Page not found!');
						},
						409: function(jqXHR, status, error) {
							alert('Error: ' + error);
						},
						200: function(data) {
							alert('Saved!');
						}
					}
				});
			}

			// Load content from file into the editor
			function loadTinyMCEContent() {
				if (queryObj['id']) {
					$.ajax({
						type: 'POST',
						url: './util/notepadPost.php',
						data: {
							action: 'load', 
							notepadid: parseInt(queryObj['id'])
						},
						dataType: "json",
						statusCode: {
							404: function() {
								alert("Page not found.");
							},
							409: function(jqXHR, textStatus, error) {
								alert("Error: " + error);
							},
							200: function(data) {
								$('#notepadTitle').text(data.notepadname);
								tinymce.activeEditor.setContent(data.content);
							}
						}
					});
				}
			}
		
			$().ready(function() {
				$('textarea.tinymce').tinymce({
					// Location of TinyMCE script
					script_url : 'js/tiny_mce/tiny_mce.js',
		
					// Plugins
					plugins : "save",
					
					// Save functionality
					save_enablewhendirty : false,
					save_onsavecallback: "writeToFile",
					
					// General options
					theme : "advanced",
		
					// Editor Size
					width : "550",
					height : "600",
					
					// Theme options
					theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect,|,bullist,numlist,|,image",
					theme_advanced_buttons2 : "",
					theme_advanced_buttons3 : "",
					theme_advanced_buttons4 : "",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : false,
					theme_advanced_resizing : false,
		
					// Example content CSS (should be your site CSS)
					content_css : "css/content.css",
		
					// Drop lists for link/image/media/template dialogs
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js",
				});

				setTimeout("autosave()", 10000);
			});
		</script>
	</head>
	<body onload="loadTinyMCEContent();" onunload="loadTinyMCEContent();">
		<div id="main">
			<div id="page_header">
				<h1 id="notepadTitle">Notepad Title Here</h1>
			</div>
			<div id="middle">
				<div id="container">
					<textarea id="elm1" name="elm1" rows="15" cols="80" class="tinymce">
					</textarea>
				</div>
			</div>
			<div id="left">
				<b>Left Column: <em>100px</em></b>
			</div>
			<div id="right">
				<b>Right Column: <em>100px</em></b>
			</div>
		</div>
	</body>
</html>
