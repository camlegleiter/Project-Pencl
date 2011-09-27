<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Canvas - Pencl</title>
		<!--<link rel="stylesheet" type="text/css" href="css/styles.css" />-->
		<link rel="stylesheet" type="text/css" href="css/testcanvas.css" />
		
		<script type="text/javascript" src="js/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/jquery/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/jquery/jquery.mousewheel.min.js"></script>
		
		<script type="text/javascript" src="js/tabby.js"></script>
		<script type="text/javascript" src="js/editor.js"></script>
		<script type="text/javascript" src="js/vertical.slider.js"></script>
	</head>
	<body>
		<div id="main">
			<div id="page_header">
				<h1>Notepad Title Here</h1>
			</div>
			<div id="middle">
				<div class="scroll-container">
					<div class="scroll-pane" style="overflow-x: hidden; overflow-y: hidden; ">
						<div class="scroll-content">
							<div class="scroll-content-item">1</div>
							<div class="scroll-content-item ">2</div>
							<div class="scroll-content-item ">3</div>
							<div class="scroll-content-item remove">4</div>
							<div class="scroll-content-item remove">5</div>
							<div class="scroll-content-item remove">6</div>
							<div class="scroll-content-item remove">7</div>
							<div class="scroll-content-item remove">8</div>
							<div class="scroll-content-item remove">9</div>
							<div class="scroll-content-item " id="expand">Click to increase scroll content</div>
							<div class="scroll-content-item" id="contract">Click to decrease scroll content</div>
						</div>
					</div>
				</div>
			</div>
			<div id="left">
				<b>Left Column: <em>225px</em></b>
			</div>
			<div id="right">
				<b>Right Column: <em>250px</em></b>
				<hr>
				<a id="addtext" href="#">Add a text element</a>
				<br>
				<a id="addsketch" href="#">Add a sketch element</a>
				<p>
					<label>Drawing tool: <select id="dtool"> 
						<option value="line">Line</option> 
						<option value="rect">Rectangle</option> 
						<option value="pencil">Pencil</option> 
					</select></label>
				</p> 
			</div>
		</div>
	</body>
</html>