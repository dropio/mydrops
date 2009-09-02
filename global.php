<?php

function goBack($direct) {
	echo "<br><a href='".$direct.".php'>Click here to go back";
	if($direct == 'login')
	{
		echo " to the Login Page</a>";
	}
	if($direct == 'manage')
	{
		echo " to the Main Page</a>";
	}
	if($direct == 'control')
	{
		echo " to the Control Panel</a>";
	}
	if($direct == 'register')
	{
		echo " to the Registration Page</a>";
	}
	if($direct == 'recover')
	{
		echo " to the Recovery Page</a>";
	} else {
		echo ".</a>";
	}
}

function array_push_assoc($array, $key, $value) {
	$array[$key] = $value;
	return $array;
}

?>
