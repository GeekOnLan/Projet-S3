<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once ('includes/connectedMember.inc.php');

if(isset($_GET['idLan'])&&is_numeric($_GET['idLan']) && isset($_GET['idTournoi'])&&is_numeric($_GET['idTournoi'])) {
	$lans = Member::getInstance()->getLAN();
	$lan = null;
	if ($_GET['idLan'] <= sizeof($lans) - 1) {
		$lan = $lans[$_GET['idLan']];
	}else
		header('Location: message.php?message=un problème est survenu');
	
	$tournois=$lan->getTournoi();
	$tournoi = null;
	if ($_GET['idTournoi'] <= sizeof($tournois) - 1) {
		$tournoi = $tournois[$_GET['idTournoi']];
	}else
		header('Location: message.php?message=un problème est survenu');
	
	var_dump($tournoi->getEquipe());
}
else {
	header('Location: message.php?message=un problème est survenu');
}