<?php
#Gestion de l'affichage

include("./includes/pages/header.php");

$page = (isset($_GET['page'])) ? $_GET['page'] : "";

switch ($page) {
    #Pages "vitrine"
    case "":
    case "index":
    case "accueil":
        include("./includes/pages/accueil.php");
        break;

    case "mentions-legales":
        include("./includes/pages/mentionslegales.php");
        break;

    case "contact":
        include("./includes/pages/contact.php");
        break;


    #Pages espace membre
    case "espace-membre":
        validateSession($db, $connection);
        $page_membre = (isset($_GET['em'])) ? $_GET['em'] : ""; #Recuperation GET page membre.
        $page_membre = (!isset($_SESSION['LoggedIn']) || !$_SESSION['LoggedIn']) ? "login" : $page_membre; #Modification page demandée si utilisateur non connecté.
        
        switch ($page_membre) {

            case "login":
                include("./includes/pages/espacemembre-auth.php");
                break;

            case "logout":
                die("<script>window.location.href='../logout.php';</script>");
                break;
            
            case "sondage":
                include("./includes/pages/espacemembre-sondage.php");
                break;
            
            case "emails":
                include("./includes/pages/espacemembre-emails.php");
                break;
            
            case "compte":
                include("./includes/pages/espacemembre-compte.php");
                break;

            default:
                include("./includes/pages/espacemembre-accueil.php");
                break;
        }
        break;


    #Pages d'erreur
    case "401":
    case "403":
        include("./includes/pages/403.php");
        break;
    case "404":
    default:
        include("./includes/pages/404.php");
        break;

}


include("./includes/pages/footer.php");