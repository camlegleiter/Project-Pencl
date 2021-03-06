<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';

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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/styles.css" >
<link rel="stylesheet" type="text/css" href="css/nivo-slider.css" >

<!-- Load jQuery -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("jquery", "1");
</script>


<?php
//Put this at the end of the <head> tag to track
include 'includes/topbar_header.php';
include 'includes/tracker.php';
?>

</head>

<body>
<?php
//Must be first thing in the <body> tag to function correctly
include 'includes/topbar.php';
?>

<div id="page">

    <h1>Coming Soon</h1>
    
    <div id="slideshowContainer">
        <div id="slideshow">
            <img src="img/slides/slide1.jpg" width="454" height="169" alt="'Best app ever' - Gizmodo">
            <img src="img/slides/slide2.jpg" width="454" height="169" alt="'If this were a game: 10/10' - IGN">
            <img src="img/slides/slide3.jpg" width="454" height="169" alt="'Steve jobs would use this' - Apple">
            <img src="img/slides/slide4.jpg" width="454" height="169" alt="'This deserves an...A...' - Simanta Mitra">
        </div>
	</div>
        
    <h2>Subscribe</h2>
    
    <form method="post" action="index.php">
    	<input type="text" id="email" name="email" value="<?php echo $msg?>">
        <input type="submit" value="Submit" id="submitButton">
    </form>
    
</div>

	<!-- Javascript (Called after jQuery) -->
	<script type="text/javascript" src="js/jquery/jquery.nivo.slider.js"></script>
	<script type="text/javascript" src="js/script.js"></script>

<?php
//Must be last thing in the <body> tag to function correctly
include 'includes/footerbar.php';
?>

</body>
</html>
