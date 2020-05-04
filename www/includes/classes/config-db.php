<?php

$db = array();

$db['ipdatabase'] = 'mysql:host=;dbname=';
$db['ip'] = '';
$db['login'] = '';
$db['password'] = '';

$db['prefix'] = 'EM' . '_';

$connection = mysqli_connect($db['ip'], $db['login'], $db['password'], $db['login']); 
mysqli_set_charset($connection, "utf8");

?>