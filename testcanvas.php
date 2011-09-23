<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Canvas - Pencl</title>
		<!--<link rel="stylesheet" type="text/css" href="css/styles.css" />-->
		<link rel="stylesheet" type="text/css" href="css/testcanvas.css" />
		
		<script type="text/javascript" src="js/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/jquery/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/editor.js"></script>
		<script type="text/javascript" src="js/tabby.js"></script>
	</head>
	<body>
<div id="main">
	<div id="page_header">
		<h1>Notepad Title Here</h1>
	</div>
	<div id="middle">
		<div id="content">
			<div id="editor"></div>
		</div>
	</div>
	<div id="left">
		<b>Left Column: <em>225px</em></b>
	</div>
	<div id="right">
		<b>Right Column: <em>250px</em></b>
		<br>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#adddiv").click(createTextBox);
			});
		</script>
		<a id="adddiv" href="#">Add a &lt;div&gt; element to the editor</a>
	</div>
</div>
	</body>
</html>