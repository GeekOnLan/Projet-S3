<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

//on verifie que se n'est pas l'utilisateur qui appel la page
if (!verify($_SERVER, 'HTTP_REFERER'))
    header('Location: message.php?message=un problème est survenu');

if(isset($_GET['idEquipe']) && is_numeric($_GET['idEquipe'])) {
    try {
        $equipe = Equipe::createFromId($_GET['idEquipe']);

        $equipe->removeMember(Member::getInstance()->getId(),"vous ete partie de l'equipe '".$equipe->getNomEquipe()."'","Le membre '".Member::getInstance()->getPseudo()."' est partie de votre equipe '".$equipe->getNomEquipe()."'");
        header("Location: message.php?message=vous ete bien partie de l'equipe");
    } catch (Exception $e) {
        header('Location: message.php?message=un problème est survenu');
    }
}
