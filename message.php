<?php

//page pour les erreur comme ca un seul css pour les erreur :p

require_once('includes/autoload.inc.php');
require_once('includes/utility.inc.php');

if(verify($_GET,'message')){
    $webpage = new GeekOnLanWebpage("GeekOnLan - Accueil");
    $webpage -> appendContent(addslashes("<p>".$_GET['message']."</p>"));
    $webpage->appendCssUrl("style/regular/message.css" , "screen and (min-width: 680px)");
    echo $webpage->toHTML();
}
else
    header('Location: index.php');