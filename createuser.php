<?php
session_start();
if(isset($_SESSION['user']))
	header('location: manage.php');
include "digest.php";
include "global.php";

$w=(trim(strtolower($_POST['passwd'])));
$x=(trim(strtolower($_POST['passwd2'])));
$y=(trim(strtolower($_POST['user'])));
$z=(trim(strtolower($_POST['user2'])));
$digest=digest($w);

//Check for bad foregin characters and to prevent attacks
$invalidPass1=(preg_match('/[^a-zA-Z0-9\s@.!@#$%^&*()-=_+\[\]<>,.?\/\{\}\|:;]/', $w));
$invalidPass2=(preg_match('/[^a-zA-Z0-9\s@.!@#$%^&*()-=_+\[\]<>,.?\/\{\}\|:;]/', $x));
$invalidUser2=(preg_match('/[^a-zA-Z0-9\s@.-_+]/', $z));
$invalidUser1=(preg_match('/[^a-zA-Z0-9\s@.-_+]/', $y));

$con = mysql_connect("localhost","syeung","huntman");

list($emailName,$mailDomain)=explode("@",$y);
list($emailName2,$mailDomain2)=explode("@",$z);

if((!$invalidPass1) && (!$invalidPass2))
{
	if((!$invalidUser1) && (!$invalidUser2))
	{
		if (($w) && ($x) && ($y) && ($z) && ($w != "") && ($x !="") && ($y != "") && ($z != ""))
		{
			if(($mailDomain) && ($mailDomain2))
			{
				//Check if domain supports mail exchange
				if((checkdnsrr($mailDomain,"MX")) && (checkdnsrr($mailDomain2,"MX")))
				{
					if ($w == $x)
					{
						if ($y == $z)
						{
							if (!$con)
							{
							        die('Could not connect:'.mysql_error());
							}
							mysql_select_db("dropmanager",$con);
							$result = mysql_query(sprintf("SELECT user FROM users WHERE user='%s'", mysql_real_escape_string($y)));
							$checkuser=mysql_fetch_array($result);
							if ($y != $checkuser['user'])
							{
								$createuser=(sprintf("INSERT INTO users (user,pass) VALUES ('%s','%s')", mysql_real_escape_string($y), mysql_real_escape_string($digest)));
								if(!mysql_query($createuser))
								{
									die('Error: '.mysql_error());
								}
								mysql_close($con);
								header('Location: return.php');
							} else {
								echo "This e-mail has been taken.";
								goBack(register);
							}
						} else {
							echo "The given e-mails are not the same.";
							goback(register);
						}
					} else {
						echo "The given passwords are not the same.";
						goBack(register);
					}
				} else {
					echo "Your e-mail is not valid.";
					goBack(register);
				}
			} else {
				echo "Your e-mail is not valid.";
				goBack(register);
			}
		} else {
			echo "A field is empty.";
			goback(register);
		}
	} else {
		echo "Invalid characters in e-mail.";
		goBack(register);
	}
} else {
	echo "Invalid characters in password.";
	goBack(register);
}
?>
