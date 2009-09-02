<?php
session_start();
if(!isset($_SESSION['user']))
	header('Location: login.php');
$manageid=$_SESSION['user_id'];
$w=(trim($_GET['dropname']));
$x=(trim($_GET['dropid']));

include 'config.php';

if((!isset($w)) && (!isset($y)))
	header('Location: manage.php');

$con=mysql_connect($mysqlserver, $mysqluser, $mysqlpass);
if(!$con)
{
	die('Could not connect: '.mysql_error());
}
mysql_select_db("dropmanager", $con);
$select=(mysql_query(sprintf("SELECT token FROM drops WHERE name = '%s' AND id = '%s' AND user_id = '$manageid'", mysql_real_escape_string($w), mysql_real_escape_string($x))));
$lines=mysql_fetch_array($select, MYSQL_ASSOC);
$y = $lines['token'];

echo ($w.' '.$x.' ');

$ch = curl_init();
$data = array('_method' => 'delete', 'version' => '1.0');
$data['api_key'] = $apiKey;
$data['token'] = $y;
$url='http://api.drop.io/drops/'.$w;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_exec($ch);
curl_close($ch);
?>
