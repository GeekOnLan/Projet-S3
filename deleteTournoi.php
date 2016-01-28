<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

//on verifie que se n'est pas l'utilisateur qui appel la page
if(verify($_SERVER,'HTTP_REFERER'))
	header('Location: message.php?message=Un problème est survenu');

if(!isset($_GET['idLan']) || empty($_GET['idLan']) || !isset($_GET['idTournoi']) || empty($_GET['idTournoi']))
	header('Location: message.php?message=Un problème est survenu');
	
$member = Member::getInstance();
try {
		$tournoi =  $member->getLAN()[$_GET['idLan']]->getTournoi()[$_GET['idTournoi']];
		$tournoi->delete("le createur du tournoi ".$tournoi->getNomTournoi()." l'a supprimée");
		header('Location: message.php?message=Votre tournoi a bien été supprimée');
}
catch(Exception $e){
	header('Location: message.php?message=Un problème est survenu');
}