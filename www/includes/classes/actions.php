<?php
//Fichier qui gère l'ensemble des formulaires POST / GET


if (isset($_POST['action'])) {

    switch ($_POST['action']) {
        
        #Page de connexion
        case "login":
            header("Refresh: 0; url=/espace-membre/#scroll");
            $remember = (isset($_POST['auth-remember']) && $_POST['auth-remember'] == "on") ? true : false;
            login($_POST['auth-email'], $_POST['auth-passwd'], $remember, $db, $connection);
            break;

        case "register":
            header("Location:" . register($_POST['register-code'], $_POST['register-email'], $_POST['register-passwd1'], $_POST['register-passwd2'], $db, $connection));
            break;


        #Page sondages
        case "poll":
            poll($_POST['poll-id'], $_POST['poll-response'], $db, $connection);
            header("Refresh: 0; url=/espace-membre/sondage/" . $_POST['poll-id'] . "/#scroll");
            break;

        case "poll-new":
            newPoll($_POST['question'], $_POST['response'], $_POST['type'], $db, $connection);
            header("Refresh: 0; url=/espace-membre/sondage/#scroll");
            break;


        #Page compte
        case "edit-email":
            editEmail($_POST['passwd'], $_POST['newemail'], $db, $connection, $em);
            header("Refresh: 0; url=/espace-membre/compte/#scroll");
            break;

        case "edit-passwd":
            editPasswd($_POST['passwd'], $_POST['passwd1'], $_POST['passwd2'], $db, $connection, $em);
            header("Refresh: 0; url=/espace-membre/compte/#scroll");
            break;

        case "download-data":
            downloadData($_POST['passwd'] ,$db, $connection);
            header("Refresh: 0; url=/espace-membre/compte/#scroll");
            break;
        

        #Page contact
        case "contact":
            contactForm($_POST['name'], $_POST['email'], $_POST['message'], $_POST['g-recaptcha-response'], $em, $recaptcha['private']);
            header("Refresh: 0; url=/contact#scroll");
            break;
    }
  
}



/**
 * Envoi d'un message par le formulaire de contact.
 * 
 * @param string            $name       -   Nom de l'expediteur
 * @param string            $email      -   E-mail de l'expediteur
 * @param string            $message    -   Message
 * @param string            $recaptcha  -   Jeton recaptcha
 * @param array             $em         -   Identifiants de compte e-mail d'envoi de notifications. 
 * @param array             $secretKey  -   Clés privée de recaptcha
 * 
 * @return void
 */
function contactForm($name, $email, $message, $recaptcha, $em, $secretKey) {

    if (isset($name) && $name != "" && isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && isset($message) && $message != "") {

        $responseKey = $_POST['g-recaptcha-response'];
        $userIp = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIp";

        $response = file_get_contents($url);
        $response = json_decode($response);

        if ($response->success) {

            #Envoi du mail au secretaire
            sendMailwosmtp($em['address'], "[bde-maite.fr] Message par le formulaire de contact", "Nom de l'expediteur : " . $name . "\n" . "E-mail de l'expediteur : " . $email . "\n" . "Message : \n" . $message);
            
            #Envoi de l'accusé de reception
            sendMail($em, $_SESSION['Data']['EMail'], "[bde-maite.fr] Accusé de réception", "Accusé de réception", "Bonjour, <br />Votre message a bien été transmis.<br />Vous recevrez une réponse dans les plus brefs délais", "https://www.bde-maite.fr/", "Acceder au site du BDE");

            $_SESSION['Hints'] = array("type" => "info", "form" => "contact", "message" => "Votre message a été envoyé.", "ttl" => 1);

        } else {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "contact", "message" => "Veuillez compléter le captcha.", "ttl" => 1);
        }

    } else {
        $_SESSION['Hints'] = array("type" => "warning", "form" => "contact", "message" => "Veuillez remplir tous les champs.", "ttl" => 1);
    }

}


/**
 * Télechargement des données utilisateur.
 * 
 * @param string            $passwd     -   Mot de passe de l'utilisateur pour autoriser l'accès.
 * @param array             $db         -   Identifiants de connexion à la base définis dans config-db.php
 * @param mysqlconnection   $connection -   Connexion BDD effectuée dans le fichier config-db.php 
 * 
 * @return void
 */
function downloadData($passwd, $db, $connection) {

    if (password_verify(hash('sha512', $passwd . $_SESSION['Data']['Salt']), $_SESSION['Data']['Password'])) {

        $bdd_prefix = $db['prefix'];

        $query = $connection->prepare("SELECT * FROM {$bdd_prefix}Credentials WHERE ID = ?");
        $query->bind_param("s", $_SESSION['Data']['ID']);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        $userData = $result->fetch_assoc();

        $user_data_file = fopen("maite-bde-fr-user-data-recover-" . $_SESSION['Data']['ID'] . ".json", "w");
        fwrite($user_data_file, json_encode($userData));
        fclose($user_data_file);

        $file = "maite-bde-fr-user-data-recover-" . $_SESSION['Data']['ID'] . ".json";

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);

        unlink($file);

    } else {
        $_SESSION['Hints'] = array("type" => "warning", "form" => "edit", "message" => "Mot de passe acutel invalide.", "ttl" => 1);
    }

}

/**
 * Changement de mot de passe de l'utilisateur.
 * 
 * @param string            $passwd     -   Mot de passe de l'utilisateur pour autoriser l'accès.
 * @param string            $passwd1    -   Nouveau mot de passe de l'utilisateur.
 * @param string            $passwd2    -   Nouveau mot de passe de l'utilisateur (confirmation).
 * @param array             $db         -   Identifiants de connexion à la base définis dans config-db.php
 * @param mysqlconnection   $connection -   Connexion BDD effectuée dans le fichier config-db.php 
 * @param array             $em         -   Identifiants de compte e-mail d'envoi de notifications. 
 * 
 * @return void
 */
function editPasswd($passwd, $passwd1, $passwd2, $db, $connection, $em) {

    $bdd_prefix = $db['prefix'];

    if ($passwd1 != "" && $passwd1 == $passwd2 && strlen($passwd1) >= 8) {
        if (password_verify(hash('sha512', $passwd . $_SESSION['Data']['Salt']), $_SESSION['Data']['Password'])) {

            #Generation sel
            $randomString = randomString(16);

            $newPasswd = password_hash(hash('sha512', $passwd1 . $randomString), PASSWORD_DEFAULT, ['cost' => 12]);
            $newSalt = $randomString;

            $id = $_SESSION['Data']['ID'];

            $query = $connection->prepare("UPDATE {$bdd_prefix}Credentials SET Password = ?, Salt = ? WHERE ID = ?");


            $query->bind_param("ssi", $newPasswd, $newSalt, $id);
            $query->execute();
            $query->close();

            login($_SESSION['Data']['EMail'], $passwd1, false, $db, $connection);

            sendMail($em, $_SESSION['Data']['EMail'], "[bde-maite.fr] Modification de votre compte", "Modification de votre compte", "Bonjour " . $_SESSION['Data']['FirstName'] . " " . $_SESSION['Data']['LastName'] . ", <br />Votre mot de passe a bien été modifié.", "https://www.bde-maite.fr/espace-membre/compte/#scroll", "Acceder à mon compte");

            $_SESSION['Hints'] = array("type" => "info", "form" => "edit", "message" => "Votre mot de passe a été mise à jour.", "ttl" => 1);

        } else {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "edit", "message" => "Mot de passe acutel invalide.", "ttl" => 1);
        }

    } else {
        if ($passwd1 != $passwd2) {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "edit", "message" => "Les mots de passes ne correspondent pas.", "ttl" => 1);
        } elseif (strlen($passwd1) < 8) {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "edit", "message" => "Veuillez choisir un mot de passe d'au moins 8 caractères.", "ttl" => 1);
        } else {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "edit", "message" => "Veuillez choisir un mot de passe valide.", "ttl" => 1);
        }
    }


}

/**
 * Changement d'email de l'utilisateur
 * 
 * @param string            $passwd     -   Mot de passe de l'utilisateur pour autoriser l'accès.
 * @param string            $email      -   Nouvelle adresse e-mail de l'utilisateur.
 * @param array             $db         -   Identifiants de connexion à la base définis dans config-db.php
 * @param mysqlconnection   $connection -   Connexion BDD effectuée dans le fichier config-db.php 
 * @param array             $em         -   Identifiants de compte e-mail d'envoi de notifications. 
 * 
 * @return void
 */
function editEmail($passwd, $email, $db, $connection, $em) {

    $bdd_prefix = $db['prefix'];

    if (isset($email) && $email != "" && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (password_verify(hash('sha512', $passwd . $_SESSION['Data']['Salt']), $_SESSION['Data']['Password'])) {

            $query = $connection->prepare("UPDATE {$bdd_prefix}Credentials SET EMail = ? WHERE ID = ?");

            $id = $_SESSION['Data']['ID'];

            $query->bind_param("si", $email, $id);
            $query->execute();
            $query->close();

            $_SESSION['Data']['EMail'] = $email;

            sendMail($em, $_SESSION['Data']['EMail'], "[bde-maite.fr] Modification de votre compte", "Modification de votre compte", "Bonjour " . $_SESSION['Data']['FirstName'] . " " . $_SESSION['Data']['LastName'] . ", <br />Votre adresse e-mail a bien été modifié.", "https://www.bde-maite.fr/espace-membre/compte/#scroll", "Acceder à mon compte");

            $_SESSION['Hints'] = array("type" => "info", "form" => "edit", "message" => "Votre adresse e-mail a été mise à jour.", "ttl" => 1);

        } else {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "edit", "message" => "Mot de passe acutel invalide.", "ttl" => 1);
        }

    } else {
        $_SESSION['Hints'] = array("type" => "warning", "form" => "edit", "message" => "Veuillez renseigner une adresse e-mail valide.", "ttl" => 1);
    }


}


/**
 * Creation d'un sondage.
 * 
 * @param string            $pollQuestion       -   Question du sondage
 * @param array             $pollResponses      -   Réponses du sondage
 * @param array             $pollType           -   Type du sondage (Choix unique/multiple)
 * @param array             $db                 -   Identifiants de connexion à la base définis dans config-db.php
 * @param mysqlconnection   $connection         -   Connexion BDD effectuée dans le fichier config-db.php 
 * 
 * @return void
 */
function newPoll($pollQuestion, $pollResponses, $pollType, $db, $connection) {

    $bdd_prefix = $db['prefix'];
    if (isset($pollQuestion) && $pollQuestion != "" && isset($pollResponses)) {

        $responseStr = "";
        foreach ($pollResponses as $response) {
            if ($response != "") {
                $responseStr .= $response . "$";
            }
        }
        $responseStr = substr($responseStr, 0, strlen($responseStr) - 1);

        $pollStatus = "Ouvert";
        $pollResults = "{}";
        $pollTypeI = ($pollType[0] == "Multiple") ? "Multiple" : "Unique";

        $query = $connection->prepare("INSERT INTO {$bdd_prefix}Polls (Question, Type, Responses, Status, Results) VALUES (?,?,?,?,?)");

        $query->bind_param("sssss", $pollQuestion, $pollTypeI, $responseStr, $pollStatus, $pollResults);
        $query->execute();
        $query->close();

    } else {
        $_SESSION['Hints'] = array("type" => "warning", "form" => "register", "message" => "Veuillez renseigner tous les champs obligatoires.", "ttl" => 1);
    }

}

/**
 * Voter dans un sondage.
 * 
 * @param int               $pollId             -   ID(entifiant) du sondage
 * @param array             $pollResponse       -   Réponse(s) de l'utilisateur au sondage
 * @param array             $db                 -   Identifiants de connexion à la base définis dans config-db.php
 * @param mysqlconnection   $connection         -   Connexion BDD effectuée dans le fichier config-db.php 
 * 
 * @return void
 */
function poll($pollId, $pollResponse, $db, $connection) {


    $bdd_prefix = $db['prefix'];

    $query = $connection->prepare("SELECT * FROM {$bdd_prefix}Polls WHERE ID = ?");
    $query->bind_param("i", $pollId);
    $query->execute();
    $result = $query->get_result();
    $query->close();
    $pollData = $result->fetch_assoc();

    if ($pollData['Status'] == "Ouvert") {

        if (isset($pollResponse) && sizeof($pollResponse) > 0) {


            if (!hasParticipated($pollData['Results'], $_SESSION['Data']['ID'])) {

                if (!(sizeof($pollResponse) > 1 && !$pollData['Type'] == "Multiple")) {



                    $pollDataResults = $pollData['Results'];

                    foreach ($pollResponse as $vote) {
                        $pollDataResults = substr($pollDataResults, 0, strlen($pollDataResults) - 1);
                        $pollDataResults .= ', "' .  $_SESSION['Data']['ID'] . '":' . $vote . "}";
                    }

                    $pollDataResults = (substr($pollDataResults, 0, 2) == "{ ," || substr($pollDataResults, 0, 2) == "{,") ? "{" . substr($pollDataResults, 3, strlen($pollDataResults)) : $pollDataResults;
                    
                    
                    $query = $connection->prepare("UPDATE {$bdd_prefix}Polls SET Results = ? WHERE ID = ?");
                    $query->bind_param("si", $pollDataResults, $pollId);
                    $query->execute();
                    $query->close();
                    
                    $_SESSION['Hints'] = array("type" => "info", "form" => "poll", "message" => "Votre vote a bien été enregistré.", "ttl" => 1);

                } else {
                    $_SESSION['Hints'] = array("type" => "warning", "form" => "poll", "message" => "Vous n'avez pas le droit de choisir plusieurs réponses dans ce sondage.", "ttl" => 1);
                }

            } else {
                $_SESSION['Hints'] = array("type" => "warning", "form" => "poll", "message" => "Vous avez déjà voté à ce sondage.", "ttl" => 1);
            }

        } else {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "poll", "message" => "Veuillez sélectionner au moins une réponse.", "ttl" => 1);
        }
        
    } else {
        $_SESSION['Hints'] = array("type" => "warning", "form" => "poll", "message" => "Impossible de voter : Ce sondage est clos.", "ttl" => 1);
    }


}


/**
 * Se connecter à l'espace membre.
 * 
 * @param string            $email              -   Adresse e-mail de l'utilisateur
 * @param string            $passwd             -   Mot de passe de l'utilisateur
 * @param boolean           $remember           -   Se souvenir de la connexion
 * @param array             $db                 -   Identifiants de connexion à la base définis dans config-db.php
 * @param mysqlconnection   $connection         -   Connexion BDD effectuée dans le fichier config-db.php 
 * 
 * @return void
 */
function login($email, $passwd, $remember, $db, $connection) {

    if ($email != "" && $passwd != "") {

        $bdd_prefix = $db['prefix'];
    
    
        $query = $connection->prepare("SELECT * FROM {$bdd_prefix}Credentials WHERE EMail = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        $userData = $result->fetch_assoc();
    
        if (password_verify(hash('sha512', $passwd . $userData['Salt']), $userData['Password']) || isset($_COOKIE['KeepAliveToken']) && $_COOKIE['KeepAliveToken'] == $userData['KeepAliveToken']) {

            
            if ($userData['Status'] == 'Alive') { #Verification des accès

                if ($remember) {
                    #Generation jeton
                    $randomString = randomString(16);

                    #MAJ Jeton
                    $query = $connection->prepare("UPDATE {$bdd_prefix}Credentials SET KeepAliveToken = ? WHERE ID = ?");
                    $query->bind_param("si", $randomString, $userData['ID']);
                    $query->execute();
                    $query->close();

                    #Creation COOKIE de 7J
                    setcookie("KeepAliveToken", $randomString, time() + 3600*24*7);

                    #MAJ userData
                    $query = $connection->prepare("SELECT * FROM {$bdd_prefix}Credentials WHERE EMail = ?");
                    $query->bind_param("s", $email);
                    $query->execute();
                    $result = $query->get_result();
                    $query->close();
                    $userData = $result->fetch_assoc();
                }


                #Attribution des données de session
                $_SESSION['Data'] = $userData;
                $_SESSION['Passwd'] = $passwd;
                $_SESSION['LoggedIn'] = true;
                
            } else {
                switch ($userData['Status']) {
                    case "Suspended":
                        $_SESSION['Hints'] = array("type" => "warning", "form" => "login", "message" => "Votre compte est suspendu.", "ttl" => 1);
                        $_SESSION['LoggedIn'] = false;
                        break;
                }
            }

            

        } else {
            $url = "/espace-membre/login#scroll";
            $_SESSION['Hints'] = array("type" => "warning", "form" => "login", "message" => "Identifiants de connexion invalides.", "ttl" => 1);
            $_SESSION['LoggedIn'] = false;
        }
    
    

    } else {

        $_SESSION['Hints'] = array("type" => "warning", "form" => "login", "message" => "Veuillez renseigner tous les champs obligatoires.", "ttl" => 1);
        $_SESSION['LoggedIn'] = false;

    }

}

/**
 * Fonction qui vérifie la session en cours (mot de passe toujours valide, jeton de connexion...)
 * 
 * @param array             $db                 -   Identifiants de connexion à la base définis dans config-db.php
 * @param mysqlconnection   $connection         -   Connexion BDD effectuée dans le fichier config-db.php 
 * 
 * @return void
 */
function validateSession($db, $connection) {

    if ((!isset($_SESSION) || !isset($_SESSION['Data'])) && isset($_COOKIE['KeepAliveToken']) && strlen($_COOKIE['KeepAliveToken']) == 16) {  #Validation par jeton

        $bdd_prefix = $db['prefix'];

        $query = $connection->prepare("SELECT * FROM {$bdd_prefix}Credentials WHERE KeepAliveToken = ?");
        $query->bind_param("s", $_COOKIE['KeepAliveToken']);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        $userData = $result->fetch_assoc();

        login($userData['EMail'], $userData['Password'], false, $db, $connection);


    } elseif (isset($_SESSION['Data'])) {    #Validation par paramètres de session actuels

        login($_SESSION['Data']['EMail'], $_SESSION['Passwd'], false, $db, $connection);

    }

}




/**
 * S'enregistrer à l'espace membre.
 * 
 * @param string            $regcode            -   Code d'enregistrement de l'utilisateur
 * @param string            $email              -   Email de l'utilisateur
 * @param string            $passwd1            -   Mot de passe de l'utilisateur
 * @param string            $passwd2            -   Mot de passe de l'utilisateur (confirmation)
 * @param array             $db                 -   Identifiants de connexion à la base définis dans config-db.php
 * @param mysqlconnection   $connection         -   Connexion BDD effectuée dans le fichier config-db.php 
 * 
 * @return void
 */
function register($regcode, $email, $passwd1, $passwd2, $db, $connection) {

    $url = "espace-membre#scroll";

    if ($regcode != "" && $email != "" && $passwd1 != "" && $passwd2 != "" && $passwd1 == $passwd2 && strlen($passwd1) >= 8 && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $bdd_prefix = $db['prefix'];
    
        #Verification clé d'enregistrement
        $query = $connection->prepare("SELECT * FROM {$bdd_prefix}Credentials WHERE RegistrationCode = ?");
        $query->bind_param("s", $regcode);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        $userData = $result->fetch_assoc();
    
        if (isset($userData['ID']) && $userData['ID'] != "" && $userData['Status'] == "Registration") {

            #Generation sel
            $randomString = randomString(16);


            $reg_password = password_hash(hash('sha512', $passwd1 . $randomString), PASSWORD_DEFAULT, ['cost' => 12]);
            $reg_salt = $randomString;
            $reg_email = $email;
            $reg_status = "Alive";
            $reg_code = strtoupper($regcode);

            $query = $connection->prepare("UPDATE {$bdd_prefix}Credentials SET EMail = ?, Password = ?, Salt = ?, Status = ? WHERE RegistrationCode = ?");
            $query->bind_param("sssss", $reg_email, $reg_password, $reg_salt, $reg_status, $reg_code);
            $query->execute();


            login($email, $passwd1, false,$db, $connection);


        } else {

            $_SESSION['Hints'] = array("type" => "warning", "form" => "register", "message" => "Code d'enregistrement invalide.<br />Ce code vous est délivré lors de votre entrée en tant que membre du BDE.", "ttl" => 1);
            $_SESSION['LoggedIn'] = false;

        }
    
    

    } else {
        $_SESSION['LoggedIn'] = false;
        if ($passwd1 != $passwd2) {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "register", "message" => "Les mots de passes ne correspondent pas.", "ttl" => 1);
        } elseif (strlen($passwd1) < 8) {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "register", "message" => "Veuillez choisir un mot de passe d'au moins 8 caractères.", "ttl" => 1);
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "register", "message" => "Veuillez saisir une adresse e-mail valide.", "ttl" => 1);
        } else {
            $_SESSION['Hints'] = array("type" => "warning", "form" => "register", "message" => "Veuillez renseigner tous les champs obligatoires.", "ttl" => 1);
        }

    }


    return $url;
}




?>