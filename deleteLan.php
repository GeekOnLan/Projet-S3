<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

if(verify($_SERVER,'HTTP_REFERER')) {
    $member = Member::getInstance();

    try {
        $member->getLAN()[$_GET['idLan']]->delete();
        header('Location: erreur.php?erreur=Votre lan a bien été supprimé');
    }
    catch(Exception $e){
        //header('Location: erreur.php?erreur=un probléme est survenu');
        echo $e;
    }
}
else
    header('Location: index.php');