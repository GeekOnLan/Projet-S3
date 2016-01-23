<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

//on verifie que se n'est pas l'utilisateur qui appel la page
if (verify($_SERVER, 'HTTP_REFERER')) {
    $member = Member::getInstance();
    try {
        $member->deleteAccount();
        header('Location: message.php?message=Votre compte a bien été supprimé');
    } catch (Exception $e) {
        header('Location: message.php?message=un problème est survenu');
    }
} else
    header('Location: message.php?message=un problème est survenu');
