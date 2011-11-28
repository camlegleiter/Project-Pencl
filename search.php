<?php
//Must be on top of everything to function correctly
include 'includes/headerbarFunctions.php';
?>
<?php
//Include this inside the <head> tag to require user to be logged in to view the page.
include 'includes/membersOnly.php';


if(!empty($_GET['p'])){
	$pagenumber = $_GET['p'];
}
else{
	$pagenumber = 1;
}
if(!empty($_GET['n'])){
	$listnumber = $_GET['n'];
}
else{
	$listnumber = 25;
}

$limit = $pagenumber * $listnumber;
$oldlimit = $limit - $listnumber;
$rownum = $oldlimit;

function adderror($error){
	global $errorarray;
	$errorarray[] = $error;
}

function addsuccess($success){
	global $successarray;
	$successarray[] = $success;
}
function addrow($class, $description, $teacherid, $password, $classid){
	global $rownum;
	$rownum = $rownum + 1;
	
	if (strlen($password) > 0)
	{
		$password = '<img src="img/buttons/pencl_lock.png" title="Password Protected" alt="YES">';
	}
	else
	{
		$password = '';
	}
	
	$canjoin = true;
	
	if ($teacherid == $_SESSION['id'])
	{
		$canjoin = false;
	}
	else
	{
		$classStatus = mysql_query("SELECT * FROM classmates WHERE classid='$classid' AND userid='".mysql_real_escape_string($_SESSION['id'])."'");
		if (mysql_num_rows($classStatus) != 0)
		{
			$canjoin = false;
		}
		mysql_free_result($classStatus);
	}
	
	$HTML = "
	    <tr>
			<td>$rownum</td>
			<td>$class</td>
			<td>$description</td>
			<td>".getUsername($teacherid)."</td>
			<td>$password</td>
			<td>";
	if ($canjoin)
	{
		$HTML .= "<a href='join.php?classid=".$classid."'>Join</a>";
	}
	else
	{
		$HTML .= "Enrolled";
	}
	$HTML .= "</td>
		</tr>";
		
	return $HTML;
}

function showNumItems($num){
	global $listnumber, $pagenumber;
	if($listnumber != $num){
		echo "<a href=\"?p=".$pagenumber."&n=".$num;
		if (isset($_GET['srch']))
			echo "&srch=".$_GET['srch'];
		echo "\">";
	}
	else{
		echo"<strong>";
	}
	echo $num;
	if($listnumber != $num){
		echo "</a>";
	}
	else{
	echo"</strong>";
	}
	
}

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Pencl - Coming Soon</title>

<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">


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
<div id="pagewide">
<h1>Search for Classes</h1>
<form class="clearfix" action="" method="GET">
	<input type="text" name="srch">
	<?php
 		if(!empty($_GET['n'])){
			echo '<input type="hidden" name="n" value="'.$_GET['n'].'">';
		}		
	?>
	<input type="hidden" name="p" value="1">
	<input type="submit" value="Search">
	<a href="<?php echo "?p=".$_GET['p']."&n=".$_GET['n']; ?>">Clear</a>
</form>
<div class="notebook">
<table border="1" cellpadding="5px" style="text-align:center">
	<thead>
		<tr class="head">
			<th>#</th>
			<th>Class</th>
			<th>Description</th>
			<th>Teacher</th>
			<th>Password</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php
	$srch = mysql_real_escape_string($_GET['srch']);
	if(isSet($srch)){
		$result = mysql_query("SELECT * FROM classes WHERE name LIKE '%".$srch."%' LIMIT ".$oldlimit.",".($listnumber + 1)."");
	}
	else{
		$result = mysql_query("SELECT * FROM classes LIMIT ".$oldlimit.",".($listnumber + 1)."");
	}
	$shownext = false;
	for($i = 0; $i < $listnumber + 1; $i++){
		$row = mysql_fetch_assoc($result);
		if (!$row)
			break;
		if($i != $listnumber){
			echo addrow($row['name'],$row['description'], $row['owner'], $row['password'], $row['id'] );
		}
		else{
			$shownext = true;
		}
	}
?>
	</tbody>
</table>
</div>
<?php
if ($i == 0)
{
	echo "<p>No entries on this page! <a href=\"?p=1\">Go to page 1</a></p>";
}
?>
<p>Page: <?php echo $pagenumber ?> (
<?php
if ($pagenumber != 1) 
{
	echo "<a href=\"?p=".($pagenumber-1)."&n=".$_GET['n'];
	if (isset($_GET['srch']))
		echo "&srch=".$_GET['srch'];
	echo "\">";
}
	echo "&lt; Prev";
if ($pagenumber != 1)
	echo "</a>";
	echo " | ";
if ($shownext)
{
	echo "<a href=\"?p=".($pagenumber+1)."&n=".$_GET['n'];
	if (isset($_GET['srch']))
		echo "&srch=".$_GET['srch'];
	echo "\">";
}
	echo"Next &gt;";
if ($shownext)
	echo "</a>";
	
?>
)    Show: <?php showNumItems(10);echo" ";showNumItems(25);echo" ";showNumItems(50);echo" ";showNumItems(100);echo" ";showNumItems(200);echo" "; ?></p>
</div>
</body>
</html>
