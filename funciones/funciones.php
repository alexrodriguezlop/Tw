<?php
function iconectar(){
	$link = new mysqli('localhost', 'ejercicio_pw', 'pass_ejercicio_pw','75162276r');
	if ($link->connect_errno) {
		die('No pudo conectarse: ' . $link->connect_error);
	}
	$link->set_charset('utf8');
	return $link;
}


?>