<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

//on verifie que se n'est pas l'utilisateur qui appel la page
if (!verify($_SERVER, 'HTTP_REFERER'))
    header('Location: message.php?message=Un problème est survenu');

if(isset($_GET['idMembre']) && is_numeric($_GET['idMembre']) && isset($_GET['idEquipe']) && is_numeric($_GET['idEquipe'])){
   try {
       $id = $_GET['idMembre'];
       $membre = Member::createFromId($_GET['idMembre']);
       $equipe = Equipe::createFromId($_GET['idEquipe']);

       if(!$equipe->isFromLanMember(Member::getInstance()->getId())){
           header('Location: message.php?message=Un problème est survenu');
       }

       $equipe->removeMember($id, "Vous avez été exclu de l'équipe '" . $equipe->getNomEquipe() . "' par le gérant de la LAN","Le membre '".$membre->getPseudo()."' a été exclu de votre équipe '".$equipe->getNomEquipe()."' par le gérant de la LAN");
       header("Location: message.php?message=Le membre a bien été exclu de cette équipe");
   } catch (Exception $e) {
       header('Location: message.php?message=Un problème est survenu');
   }
}
elseif(isset($_GET['idEquipe']) && is_numeric($_GET['idEquipe'])) {
    try {
        $equipe = Equipe::createFromId($_GET['idEquipe']);

        if(!$equipe->isFromLanMember(Member::getInstance()->getId())){
            header('Location: message.php?message=Un problème est survenu');
        }

        $equipe->delete("Votre équipe '".$equipe->getNomEquipe()."' a été exclu par le gérant de la LAN");
        header("Location: message.php?message=Cette équipe a bien été exclue");
    } catch (Exception $e) {
        header('Location: message.php?message=Un problème est survenu');
    }
}
