<?php
session_start();
if(!isset($_SESSION['user']))
	header('Location: login.php');
$manageid=$_SESSION['user_id'];

include "config.php";

$w=(trim($_POST['dropname']));
$y=(trim($_POST['dropid']));
if (($w) && ($y))
{
        $con=mysql_connect($mysqlserver,$mysqluser,$mysqlpass);
        if (!$con)
        {       
                die('Could not connect: '.mysql_error());
        }       
	mysql_select_db($mysqldb,$con);
	$select=(mysql_query(sprintf("SELECT token FROM drops WHERE name = '%s' AND id = '%s' AND user_id = '$manageid'", mysql_real_escape_string($w), mysql_real_escape_string($y))));
	$result=mysql_fetch_array($select, MYSQL_ASSOC);
	$x=$result['token'];

        $delete="DELETE FROM drops WHERE name = '$w' AND token = '$x' AND id = '$y' AND user_id = '$manageid'";

        if (!mysql_query($delete))
        {
        die('Error: '.mysql_error());
        }
        mysql_close($con);
}
?>
