<?php

require_once('includes/autoload.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/requestUtils.inc.php');
require_once('includes/connectedMember.inc.php');

//TODO verifier les redirection a la fin du programme

//recuperation du membre
$membre = Member::getInstance();

//verification des information pour l'equipe
if(!isset($_GET['idEquipe']) || !is_numeric($_GET['idEquipe']))
    echo "<div style='font-size : 5em'>Probleme parametre</div>";
    //header('Location: message.php?message=un problème est survenu');

//creation de l'equipe
$equipe = null;
try{
    $equipe = Equipe::createFromId($_GET['idEquipe']);
}
catch (Exception $e){
    echo "<div style='font-size : 5em'>Probleme creation tournoi</div>";
    echo $e;
    //header('Location: message.php?message=un problème est survenu');
}

//on verifi que le membre fait bien parti de l'equipe
if(!$equipe->isInEquipe($membre->getId()))
    echo "<div style='font-size : 5em'>Membre pas dans equipe</div>";
    //header('Location: message.php?message=un problème est survenu');

//TODO Fin de verification

//on cree la webpage
$webPage = new GeekOnLanWebpage("GeekOnLan - gestion de l'equipe");
$webPage->appendCssUrl("style/regular/gererEquipe.css", "screen and (min-width: 680px)");

//on regarde si le membre est le createur
if($equipe->getCreateur()->getId() == $membre->getId()){
    echo "createur";
}
//sinon le membre ne peut juste que partire de l'equipe
else{
    echo "membre";
}
