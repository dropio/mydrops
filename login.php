<?php
session_start();
if(isset($_SESSION['user']))
	header('Location: manage.php');
?>
<html>
<head>
<link rel='stylesheet' type='text/css' href='drop.css'/>
<title>myDrops Login Page</title>
</head>
<body>

<div id='loginset'>
<br>
<h1>myDrops Login</h1>
<form id='loginforms' method="post" action="verify.php">
E-mail: <input type="text" name="user" />
<br>
Password: <input type="password" name="passwd" />
<input type="submit" value="Login to myDrops" />
</form>
<span><a href="register.php">Register here</a></span> | 
<span><a href="recover.php">Forgot Your Password?</a></span>
</div>

</body></html>

