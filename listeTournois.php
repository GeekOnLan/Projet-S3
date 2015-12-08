<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once ('includes/connectedMember.inc.php');

if(isset($_GET['idLan'])&&is_numeric($_GET['idLan'])) {

	$lans = Member::getInstance()->getLAN();
	$lan = null;
	if ($_GET['idLan'] <= sizeof($lans) - 1) {
		$lan = $lans[$_GET['idLan']];
	}else
		echo "trop grand";
		//header('Location: message.php?message=un problème est survenu');
	
	$tournois=$lan->getTournoi();
	$page = new GeekOnLanWebpage("GeekOnLan - Tournois");
	
	if(sizeof($tournois)==0){
		echo "pas de tournoi";
	}
	else{
		foreach ($tournois as $tournoi){
			$page->appendContent(toString($tournoi));
		}
	}
	echo $page->toHTML();

}
else {
	echo "idLan pas la";
	//header('Location: message.php?message=un problème est survenu');
}

function toString(Tournoi $tournoi){
	$equipe = sizeof($tournoi->getEquipe())."/".$tournoi->getNbEquipeMax();
	$tour=<<<HTML
<table>
	<tr>
		<th>Nom</th>
		<th>Date et heure prévu</th>
		<th>Type Elimination<th>
		<th>Nombre d'équipe</th>
		<th>Nombre de personnes par equipes</th>
	</tr>
	<tr>
		<td>{$tournoi->getNomTournoi()}</td>
		<td>{$tournoi->getDateHeurPrevu()}</td>
		<td>{$tournoi->getTpElimination()}</td>
		<td>{$equipe}</td>
		<td>{$tournoi->getNbPersMaxParEquipe()}</td>
	</tr>
</table>
HTML;
	return $tour;
}