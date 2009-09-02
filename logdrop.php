<?php
session_start();
if(!isset($_SESSION['user']))
	header('Location: login.php');
$manageid=$_SESSION['user_id'];

$drop = $_POST['dropname'];
$dropid = $_POST['dropid'];

$con = mysql_connect("localhost","syeung","huntman");
if(!$con)
	die('Could not connect: '.mysql_error());
mysql_select_db("dropmanager");
$select = (mysql_query(sprintf("SELECT token FROM drops WHERE name = '%s' AND id ='%s' AND user_id = '$manageid'", mysql_real_escape_string($drop), mysql_real_escape_string($dropid))));
$grab = mysql_fetch_array($select, MYSQL_ASSOC);
mysql_close($con);
$token = $grab['token'];

//Create API complaint link signature to Drop
$qhour=time() + (1 * 60);
$sig=sha1($qhour.'+'.$token.'+'.$drop);
echo "http://drop.io/$drop/from_api/?version=1.0&signature=$sig&expires=$qhour";
?>
