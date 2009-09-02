<?php
function digest($passwd)
{
	$digest=md5($passwd);
	return "$digest";
}
