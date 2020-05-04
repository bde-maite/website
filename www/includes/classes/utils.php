<?php
#Fonctions utiles

/**
 * Fonction qui retourne une chaîne de caractères aléatoire de longueur n.
 * 
 * @param int           $n                  -       Longueur de la chaîne a generer
 * 
 * @return string
 */
function randomString($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $n; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Fonction qui retourne la source relative d'un fichier en fonction de l'emplacement actuel.
 * 
 * @param string        $relative_src       -       Chemin d'accès relatif par défaut
 * 
 * @return string
 */
function get_src($relative_src) {
    $result_src = $relative_src;
    $nb = substr_count($_SERVER['REQUEST_URI'], "/", 0, strlen($_SERVER['REQUEST_URI']));
    if ($nb == 1) {
        $result_src = "." . $result_src;
    } elseif ($nb == 2) {
        $result_src = "../." . $result_src;
    } elseif ($nb == 3) {
        $result_src = "../../." . $result_src;
    } elseif ($nb == 4) {
        $result_src = "../../../." . $result_src;
    }
    return $result_src;
}

/**
 * Fonction qui retourne le nombre de membre actif i.e. de comptes enregistrés
 * 
 * @param array             $db         -   Identifiants de connexion à la base définis dans config-db.php
 * @param mysqlconnection   $connection -   Connexion BDD effectuée dans le fichier config-db.php 
 * 
 * @return int
 */
function getMemberCount($db, $connection) {

    $bdd_prefix = $db['prefix'];

    $count = 0;

    $query = $connection->prepare("SELECT * FROM {$bdd_prefix}Credentials");
    $query->execute();
    $result = $query->get_result();
    $query->close();
    while ($row = $result->fetch_assoc()) {
        if ($row['Status'] == 'Alive') {
            $count++;
        }
    }

    return $count;

}

/**
 * Fonction qui retourne si un membre a participé à un sondage en fonction des resultats du sondage et de l'id de l'utilisateur.
 * 
 * @param string        $results        -   Resultats du sondage
 * @param int           $uid            -   ID du membre
 * 
 * @return boolean
 */
function hasParticipated($results, $uid) {

    $res = false;

    $pollDataArray = json_decode($results, true);
    foreach ($pollDataArray as $user => $vote) {
        if ($user == $uid) {
            $res = true;
        }
    }

    return $res;

}
?>