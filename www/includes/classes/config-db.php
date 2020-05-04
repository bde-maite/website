<?php

$db = array();

$db['ipdatabase'] = 'mysql:host=bdemaitenfmain.mysql.db;dbname=bdemaitenfmain';
$db['ip'] = 'bdemaitenfmain.mysql.db';
$db['login'] = 'bdemaitenfmain';
$db['password'] = 'nhX3FVBMM4umXAvA';

$db['prefix'] = 'EM' . '_';

$connection = mysqli_connect($db['ip'], $db['login'], $db['password'], $db['login']); 
mysqli_set_charset($connection, "utf8");

?>