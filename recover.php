<?php
session_start();
if(isset($_SESSION['user']))
	header('location: manage.php');
?>
<html><head>
<title>Password Recovery Page</title>
<link rel='stylesheet' type='text/css' href='drop.css'/>
</head>
<body>
<div id='recoveryField'>
<br>
<h1>Password Recovery</h1>
Enter your e-mail that you log into the Drop Manager with: <br><br>
<form method='post' action='send.php'>
E-mail: <input type='text' name='userName'>
<input type='submit' value='Submit'>
</form>
Your current password will be reset and a new temporary one will be sent to you.
<br><br>
<a href='login.php'>Go Back</a>
</div>
</body></html>

