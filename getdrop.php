<?php
session_start();
if(!isset($_SESSION['user']))
	header('Location: login.php');
$manageid=$_SESSION['user_id'];

$x=(trim($_POST['dropname']));
$y=(trim($_POST['token']));

$con=mysql_connect("localhost","syeung","huntman");
if(!$con)
{
	die('Could not connect: '.mysql_error());
}
mysql_select_db('dropmanager',$con);
$select = (mysql_query(sprintf("SELECT name FROM drops WHERE name='%s' AND user_id='$manageid'", mysql_real_escape_string($x))));
$check = mysql_fetch_array($select, MYSQL_ASSOC);

//Prevent duplicates
if(!isset($check['name']))
{
	$ch = curl_init();
	$url = 'http://api.drop.io/drops/'.$x.'?version=1.0&api_key=74dc19f40c65919f3dc77b220ab749a0738e180d&format=xml&token='.$y;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_exec($ch);
	curl_close($ch);
} else {
	echo "<?xml version='1.0' encoding='UTF-8'?><drop><admin_token>dupeDrop</admin_token></drop>";
}
?>