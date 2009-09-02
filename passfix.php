<?php
session_start();
if(!isset($_SESSION['user']))
	header('Location: login.php');
include 'global.php';
echo 'Your password has been updated.';
goBack(manage);
?>
