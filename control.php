<?php
session_start();
if(!isset($_SESSION['user']))
	header('Location: login.php');
?>

<html>
<head>
<link rel='stylesheet' type='text/css' href='drop.css'/>
<title>Drop Manager Control Panel</title>
</head>
<body>

<div id='controlPanel'>
<br>
<h1>Control Panel</h1>
Enter your current password to edit:
<form method='post' action='accountedit.php'>
<input type='password' name='currentPass' size='30'>
<br><br>
<fieldset>
<legend>Edit Password</legend>
New Password: <br><input type='password' name='newPass' size='30'><br>
Confirm New Password: <br><input type='password' name='newPass2' size='30'>
</fieldset>
<br>
<center><input type='submit' value='Save Changes'>
<input type='reset' value='Reset Fields'></center>
</form>
<a href='manage.php'>Go back</a><br><br>
</div>

</body></html>
