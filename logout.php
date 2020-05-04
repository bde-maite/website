<?php
session_start();

include("./includes/classes/config-db.php");

if (isset($_COOKIE['KeepAliveToken'])) {

    $bdd_prefix = $db['prefix'];
    
    $nouv = "";

    $query = $connection->prepare("UPDATE {$bdd_prefix}Credentials SET KeepAliveToken = ? WHERE KeepAliveToken = ?");
    $query->bind_param("ss", $nouv, $_COOKIE['KeepAliveToken']);
    $query->execute();
    $query->close();
    
    setcookie("KeepAliveToken", "", time() + 1);
    unset($_COOKIE);

}

session_destroy();
header("Location: /");