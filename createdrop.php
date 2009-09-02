<?php
session_start();
if(!isset($_SESSION['user']))
	header('location: login.php');

include 'config.php';

$name=(trim($_POST['name']));
$admin=(trim($_POST['admin_password']));
$password=(trim($_POST['password']));
$premium=(trim($_POST['premium_code']));
$expire=($_POST['expiration_length']);
$gca=($_POST['guests_can_add']);
$gcc=($_POST['guests_can_comment']);
$gcd=($_POST['guests_can_delete']);

$ch = curl_init();
$data = array('version' => '1.0');
$data['api_key'] = $apiKey;
$data['name'] = $name;
$data['admin_password'] = $admin;
$data['password'] = $password;
$data['premium_code'] = $premium;
$data['expiration_length'] = $expire;
$data['guests_can_add'] = $gca;
$data['guests_can_comment'] = $gcc;
$data['guests_can_delete'] = $gcd;

$url='http://api.drop.io/drops/';
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_exec($ch);
curl_close($ch);
?>
