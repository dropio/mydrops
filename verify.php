<?php
session_start();
if(isset($_SESSION['user']))
	header('location: manage.php');

include 'digest.php';
include 'global.php';
include 'config.php';

$user=(trim(strtolower(($_POST['user']))));
$passwd=(trim(strtolower(($_POST['passwd']))));
$digest=digest($passwd);

$invalidUser=(preg_match('/[^a-zA-Z0-9\s@.-_+]/', $user));
if(!$invalidUser)
{
	$con = mysql_connect($mysqlserver,$mysqluser,$mysqlpass);
	if (!$con)
	{
	        die('Could not connect:'.mysql_error());
	}
	mysql_select_db($mysqldb,$con);

	$result=(mysql_query(sprintf("SELECT user_id,user,pass FROM users WHERE user='%s'", mysql_real_escape_string($user))));
	$check=mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_close($con);
} else {
	echo "Invalid characters in e-mail.";
	goBack(login);
}

if(($user != "") && ($passwd != "") && ($user) && ($passwd))
{
	if (($user == $check['user']) && ($digest == $check['pass']))
	{
		$_SESSION['user']=$user;
		$_SESSION['user_id']=$check['user_id'];
		header('Location: manage.php');
	} else {
		echo "Bad username or password";
		goBack(login);
	}
} else {
	echo "Missing e-mail or password.";
	goBack(login);
}
?>
