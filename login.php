<?php
/**
* @author Francisco Fernando
* @copyright 2016 LATECS
*/
require_once 'senha.class.php';
require_once 'config.php';
if (!empty($_POST)){
	$login = json_decode($_POST, true);
	$data = $database->select("login", "*", ["user" => $login[0]]);
	if (!empty($data)){ 
		if (Bcrypt::check($login[1], $data['senha'])) {
			echo 'OK';
		} else {
			echo "Login ou senha incorretos";
		}
	} else {
		echo "Login ou senha incorretos";
	}
}
?>