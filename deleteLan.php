<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

if(verify($_SERVER,'HTTP_REFERER')) {
    $member = Member::getInstance();

    try {
        $member->getLAN()[$_GET['idLan']]->delete();
        header('Location: message.php?message=Votre lan a bien été supprimé');
    }
    catch(Exception $e){
        header('Location: message.php?message=un problème est survenu');
    }
}
else
    header('Location: message.php?message=un problème est survenu');
