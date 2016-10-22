<?php
/**
* @author Francisco Fernando
* @copyright 2016 LATECS
*/
require_once 'medoo.php';

$database = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'prontoclinica',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'annatar',
    'charset' => 'utf8'
   ]);
?>