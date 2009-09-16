<?php
session_start();
if(!isset($_SESSION['user']))
	header('Location: login.php');
$manageid=$_SESSION['user_id'];

include 'config.php';

$w=(trim($_POST['dropname']));
$x=(trim($_POST['token']));
if (($_POST['dropname'] != "") && ($_POST['token'] != "") && ($w) && ($x))
{
	$con=mysql_connect($mysqlserver,$mysqluser,$mysqlpass);
	if (!$con)
	{
		die('Could not connect: '.mysql_error());
	}
	mysql_select_db($mysqldb,$con);
	$insert=(sprintf("INSERT INTO drops (user_id,name,token) VALUES ('$manageid','%s','%s')", mysql_real_escape_string($w), mysql_real_escape_string($x)));

	if (!mysql_query($insert))
	{
	die('Error: '.mysql_error());
	}

	$select=(mysql_query(sprintf("SELECT id FROM drops WHERE name = '%s' AND token = '%s' AND user_id = '$manageid'", mysql_real_escape_string($w), mysql_real_escape_string($x))));
	$grab=mysql_fetch_array($select, MYSQL_ASSOC);
	mysql_close($con);

	echo "dropInserted ";
	echo $grab['id'];
}
?>
