<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

if(verify($_SERVER,'HTTP_REFERER')) {
    $member = Member::getInstance();

    try {
        $member->getLAN()[$_GET['idLan']]->deleteLan();
        header('Location: erreur.php?erreur=Votre lan a bien �t� supprim�');
    }
    catch(Exception $e){
        header('Location: erreur.php?erreur=un probl�me est survenu');
    }
}
else
    header('Location: index.php');