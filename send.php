<?php
include 'global.php';
include 'digest.php';
include 'config.php';
require_once "Mail.php";
require_once "Mail/mime.php";

//Create 10-digit pass
$newPass=mt_rand(1000000000,9999999999);
$newDigest=digest($newPass);
$user=(trim(strtolower($_POST['userName'])));
$invalidUser=(preg_match('/[^a-zA-Z0-9\s@.-_+]/', $user));
list($emailName,$mailDomain)=explode("@",$user);

if($user)
{
	if(!$invalidUser)
	{
		if(($mailDomain) && (checkdnsrr($mailDomain,"MX")))
		{
			$con = mysql_connect($mysqlserver,$mysqluser,$mysqlpass);
			if(!$con)
			{
				die('Could not connect: '.mysql_error());
			}
			mysql_select_db('dropmanager');
			$selectUser =(mysql_query(sprintf("SELECT user FROM users WHERE user='%s'", mysql_real_escape_string($user))));
			$checkUser = mysql_fetch_array($selectUser);
			if($user == $checkUser['user'])
			{
				$renew = (sprintf("UPDATE users SET pass='%s' WHERE user='%s'", mysql_real_escape_string($newDigest), mysql_real_escape_string($user)));

				if(mysql_query($renew))
				{
					$from = "ogenboy+dropManagerBot@gmail.com";
					$to = $user;
					$subject = "Drop Manager Password Recovery";
					$body = ("Hello ".$user.",\r\rYou have requested for your password to be reset to a new temporary one. When you log in with this password, you can go and change it to something else in your Control Panel.\r\rNEW PASSWORD: ".$newPass."\r\r");
					$host = "localhost";
					$headers = array ('From' => $from, 'To' => $to, 'Subject' => $subject);
					$smtp = Mail::factory('smtp', array('host' => $host));
					$mail = $smtp->send($to, $headers, $body);
					if (PEAR::isError($mail)) {
						echo "There has been an error. Please try again.";
					} else {
						echo "Message has been sent!";
						goBack(login);
					}
				} else {
					die('Error: '.mysql_error());
				}
			} else {
				echo "There is no such user";
				goBack(recover);
			}
		} else {
			echo "Your e-mail is not valid";
			goBack(recover);
		}
	} else {
		echo "E-mail has invalid characters";
		goBack(recover);
	}
} else {
	echo "No e-mail was given";
	goBack(recover);
}
?>
