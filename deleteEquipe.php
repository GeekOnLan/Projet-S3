<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

//on verifie que se n'est pas l'utilisateur qui appel la page
if (!verify($_SERVER, 'HTTP_REFERER'))
    header('Location: message.php?message=un probl�me est survenu');

if(isset($_GET['idMembre']) && is_numeric($_GET['idMembre']) && isset($_GET['idEquipe']) && is_numeric($_GET['idEquipe'])){
    try {
        $id = $_GET['idMembre'];
        $membre = Member::createFromId($_GET['idMembre']);
        $equipe = Equipe::createFromId($_GET['idEquipe']);

        if(!$equipe->getCreateur()->getId() != Member::getInstance()->getId()){
            header('Location: message.php?message=un probl�me est survenu');
        }

        $equipe->removeMember($id, "vous avez ete exclue de l'equipe '" . $equipe->getNomEquipe() . "' par son gerant","Le membre '".$membre->getPseudo()."' ete exclue de l'equipe '" . $equipe->getNomEquipe() . "' par son gerant");
        header("Location: message.php?message=Le membre a bien ete exclue de votre equipe");
    } catch (Exception $e) {
        header('Location: message.php?message=un probl�me est survenu');
    }
}
elseif(isset($_GET['idEquipe']) && is_numeric($_GET['idEquipe'])) {
    try {
        $equipe = Equipe::createFromId($_GET['idEquipe']);

        if(!$equipe->getCreateur()->getId() != Member::getInstance()->getId()){
            header('Location: message.php?message=un probl�me est survenu');
        }

        $equipe->delete("votre equipe '".$equipe->getNomEquipe()."' a ete supprimer par sont gerant");
        header("Location: message.php?message=votre equipe a bien ete suprimmer");
    } catch (Exception $e) {
        header('Location: message.php?message=un probl�me est survenu');
    }
}
