<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Canvas - Pencl</title>

<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/canvas.css" />
<link rel="stylesheet" type="text/css" href="css/index.css" media="screen" />

<?php
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';
?>

</head>

<body>
<?php
//Must be first thing in the <body> tag to function correctly
include 'includes/headerbar.php';
?>

<div id="page_header">
	<h1>Notepad Title Here</h1>
</div>

<div id="page">
	<div id="contentwrapper">
		<div id="contentcolumn">
			<b>Content Column: <em>Fluid</em></b>
		</div>
	</div>
	<div id="leftcolumn">
		<b>Left Column</b>
		
		<div class="box_rotate">
		
			<div id="iphone">
				<div id="iphone_content">
					<p>
					Content here! Content here! Content here! Content here! Content here! Content here! Content here! Content here! 
					Content here! Content here! Content here! Content here! Content here! Content here! Content here! Content here! 
					Content here! Content here! Content here! Content here! Content here! Content here! Content here! Content here!  
					</p>
				</div>
			</div>
		
		</div>
		
	</div>
		
	<div id="rightcolumn">
		<b>Right Column</b>
	</div>
</div>
</body>
</html>
