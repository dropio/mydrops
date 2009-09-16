<?php
session_start();
if(!isset($_SESSION['user']))
	header('Location: login.php');
include 'digest.php';
include 'global.php';
include 'config.php';

$v=(trim(strtolower($_POST['currentPass'])));
$w=(trim(strtolower($_POST['newPass'])));
$x=(trim(strtolower($_POST['newPass2'])));
$digestCheck=digest($v);
$invalidPass1=(preg_match('/[^a-zA-Z0-9\s@.!@#$%^&*()-=_+\[\]<>,.?\/\{\}\|:;]/', $w));
$invalidPass2=(preg_match('/[^a-zA-Z0-9\s@.!@#$%^&*()-=_+\[\]<>,.?\/\{\}\|:;]/', $x));

$con = mysql_connect($mysqlserver,$mysqluser,$mysqlpass);
if (!$con)
{
	die('Could not connect: '.mysql_error());
}
mysql_select_db($mysqldb,$con);
$result = mysql_query("SELECT pass FROM users WHERE user='$_SESSION[user]' AND user_id='$_SESSION[user_id]'");
$passCheck=mysql_fetch_array($result, MYSQL_ASSOC);

if ($digestCheck = $passCheck['pass'])
{
	if ((!$invalidPass1) && (!$invalidPass2))
	{
		if (($w) && ($x) && ($w != "") && ($x != "") && ($w == $x))
		{
			$newDigest=digest($w);
			$alterPass=(sprintf("UPDATE users SET pass='%s' WHERE user='$_SESSION[user]' AND user_id='$_SESSION[user_id]'", mysql_real_escape_string($newDigest)));
			if(!mysql_query($alterPass))
			{
				die('Error: '.mysql_error());
			}
			header ('Location: passfix.php');
		} else {
			echo "A new password field is empty or incorrect.";
			goBack(control);
		}
	} else {
		echo "Invalid characters in new password";
		goBack(control);
	}
} else {
	echo "Current password given is incorrect.";
	goBack(control);
}
mysql_close($con);
?>
