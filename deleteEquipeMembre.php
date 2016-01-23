<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

//on verifie que se n'est pas l'utilisateur qui appel la page
if (!verify($_SERVER, 'HTTP_REFERER'))
    header('Location: message.php?message=un problème est survenu');

if(isset($_GET['idMembre']) && is_numeric($_GET['idMembre']) && isset($_GET['idEquipe']) && is_numeric($_GET['idEquipe'])){
   try {
       $id = Member::getInstance()->getId();
       $equipe = Equipe::createFromId($_GET['idEquipe']);
       $equipe->removeMember($id , "vous avez ete exclue de l'equipe ".$equipe->getNomEquipe()." par le gerant de la LAN");
       header("Location: message.php?message=Le membre a bien ete exclue de l'equipe");
   } catch (Exception $e) {
       header('Location: message.php?message=un problème est survenu');
   }
}
elseif(isset($_GET['idEquipe']) && is_numeric($_GET['idEquipe'])) {
    try {
        $equipe = Equipe::createFromId($_GET['idEquipe']);
        $equipe->delete("votre equipe ".$equipe->getNomEquipe()." a ete exclue par le gerant de la LAN");
        header("Location: message.php?message=L'equipe a bien ete exclue");
    } catch (Exception $e) {
        header('Location: message.php?message=un problème est survenu');
    }
}
