<?php
session_start();

#Affichage des message de debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

#Configurations
include("./includes/classes/config-db.php");
include("./includes/classes/config-recaptcha.php");
include("./includes/classes/config-email.php");

#Classe connexion IMAP
include_once("./includes/classes/imap.php");

#Fonctions utiles
include("./includes/classes/utils.php");

#Fonctions formulaires
include("./includes/classes/actions.php");

#Affichage de la page web
include("./includes/view.php");

#Gestion des messages "hint"
include("./includes/classes/hints.php");


/*
QUESTIONS :

Sondages :
    Qui peut en créer ?
    Combien de temps dure un sondage/qui peut clore un sondage ?
    Peut-on voir les resultats avant d'avoir voté ? Peut-on voir les resultats après avoir voté ? Sinon voir les resultats une fois le sondage clos.
    Peut-on voir qui a voté ou non ?
    Peut-on voir qui a voté quoi ?

Comptes :
    Qui peut créer des codes d'enregistrement ?
    Qui peut cloturer un compte ?
    Verification des e-mails ?

Notifications (e-mail) :
    A l'ouverture d'un sondage ?
    A la publication des resultats ?
    A H-? si l'utilisateur n'a toujours pas voté

Autre :
    Directeur de publication ?
    Creation d'un adresse e-mail en redirection style admin@bde-maite.fr ou technique@bde-maite.fr


TODO :
    30M     Notifications ;
            Wiki ;
    

*/