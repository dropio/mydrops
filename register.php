<?php
session_start();
if(isset($_SESSION['user']))
	header('Location: manage.php')
?><html>
<head>
<link rel='stylesheet' type='text/css' href='drop.css' />
<title>Registration Page</title>
</head>
<body>

<div id='registerset'>
<br>
<h1>myDrops Registration</h1>
<div id='registerforms'>
<form method="post" action="/createuser.php">
E-mail: <input type="text" name="user" /><br>
Repeat E-mail: <input type="text" name="user2" /><br>
Password: <input type="password" name="passwd" /><br>
Repeat Password: <input type="password" name="passwd2" /><br>
<input type="submit" value="Create User" />
</div>
<a href="login.php">Go Back</a>
</form>
</div>

</body></html>
