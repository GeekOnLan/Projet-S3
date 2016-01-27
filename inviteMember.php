<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

$membre = null;
$equipe = null;

//on verifie que se n'est pas l'utilisateur qui appel la page
if (!verify($_SERVER, 'HTTP_REFERER')) {
    header('Location: message.php?message=Un problème est survenu');
}
//on verifie les variables
elseif(!verify($_POST,'pseudo') || !isset($_GET['idEquipe']) || !is_numeric($_GET['idEquipe'])){
    header('Location: message.php?message=Un problème est survenu');
}
else{
    $membre = Member::createFromPseudo($_POST['pseudo']);
    $equipe = Equipe::createFromId($_GET['idEquipe']);
}
//on verifi que le n'est pas deja dans 'equipe
if($equipe->isInEquipe($membre->getId())){
    header('Location: message.php?message=Ce membre est deja dans votre equipe !');
}
elseif($equipe->getCreateur()->getId() != Member::getInstance()->getId()){
    header('Location: message.php?message=un problème est survenu');
}
else {
    try {
        $equipe->inviteMember($membre->getId());
        header('Location: message.php?message=Le membre a bien ete invite');
    }
    catch(Exception $e){
        header('Location: message.php?message=Le membre est deja inviter');
    }
}
