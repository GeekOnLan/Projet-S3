<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

//on verifie que se n'est pas l'utilisateur qui appel la page
if(verify($_SERVER,'HTTP_REFERER')) {
    $member = Member::getInstance();
    try {
        $lan =  $member->getLAN()[$_GET['idLan']];
        $lan->delete("le createur de la LAN ".$lan->getLanName()." la supprimer");
        header('Location: message.php?message=Votre lan a bien été supprimé');
    }
    catch(Exception $e){
        header('Location: message.php?message=un problème est survenu');
    }
}
else
    header('Location: message.php?message=un problème est survenu');
