<?php

require "includes/connect.php";

$msg = '';

if($_POST['email']){
	
	// Requested with AJAX:
	$ajax = ($_SERVER['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest');
	
	try{
		if(!filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)){
			throw new Exception('Invalid Email!');
		}

		$mysqli->query("INSERT INTO coming_soon_emails
						SET email='".$mysqli->real_escape_string($_POST['email'])."'");
		
		if($mysqli->affected_rows != 1){
			throw new Exception('This email already exists in the database.');
		}
		
		if($ajax){
			die('{"status":1}');
		}
		
		$msg = "Thank you!";
		
	}
	catch (Exception $e){
		
		if($ajax){
			die(json_encode(array('error'=>$e->getMessage())));
		}
		
		$msg = $e->getMessage();		
	}
}
?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/nivo-slider.css" />

</head>

<body>

<div id="page">

    <h1>Coming Soon</h1>
    
    <div id="slideshowContainer">
        <div id="slideshow">
            <img src="img/slides/slide1.jpg" width="454" height="169" alt="Coming Soon: Our Awesome Web App">
            <img src="img/slides/slide2.jpg" width="454" height="169" alt="Extensive Functionality">
        </div>
	</div>
        
    <h2>Subscribe</h2>
    
    <form method="post" action="">
    	<input type="text" id="email" name="email" value="<?php echo $msg?>" />
        <input type="submit" value="Submit" id="submitButton" />
    </form>
    
</div>

<!-- Feel free to remove this footer -->

<div id="footer">
	<div class="tri"></div>
	<h1>Pencl - Coming Soon</h1>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
<script src="js/jquery.nivo.slider.pack.js"></script>
<script src="js/script.js"></script>

</body>
</html>
