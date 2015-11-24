<?php

//page pour les erreur comme ca un seul css pour les erreur :p

require_once('includes/autoload.inc.php');
require_once('includes/utility.inc.php');

if(verify($_GET,'erreur')){
    $webpage = new GeekOnLanWebpage("GeekOnLan - Accueil");
    $webpage -> appendContent($_GET['erreur']);
    echo $webpage->toHTML();
}
else
    header('Location: index.php'.SID);