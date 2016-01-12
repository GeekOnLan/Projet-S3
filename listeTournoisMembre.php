<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once ('includes/connectedMember.inc.php');

if(isset($_GET['idLan'])&&is_numeric($_GET['idLan'])) {

	$lans = Member::getInstance()->getLAN();
	$lan = null;
	if ($_GET['idLan'] <= sizeof($lans) - 1) {
		$lan = $lans[$_GET['idLan']];
	}else
		header('Location: message.php?message=un problème est survenu');
	
	$tournois=$lan->getTournoi();
	$page = new GeekOnLanWebpage("GeekOnLan - Tournois");
	$page->appendCssUrl("style/regular/listeTournoisMembre.css", "screen and (min-width: 680px");
	
	if(sizeof($tournois)==0){
		$page->appendContent(<<<HTML
<table>
	<tr>
		<th>Aucun tournoi prévu pour cette LAN</th>
	</tr>
	<tr>
		<td><a href="creeTournoi.php?idLan={$_GET['idLan']}">Ajouter un tournoi</a></td>
	</tr>
</table>
HTML
);
	}
	else{
		$page->appendContent(<<<HTML
<table>
	<tr>
		<th>Nom</th>
		<th>Date et heure prévu</th>
		<th>Type Elimination</th>
		<th>Nombre d'équipes</th>
		<th>Nombre de personnes par équipe</th>
	</tr>
HTML
);
		$i=0;
		foreach ($tournois as $tournoi){
			$page->appendContent(toString($tournoi,$i++));
		}
		$page->appendContent(<<<HTML
	<tr>
		<td colspan=5><a href="creeTournoi.php?idLan={$_GET['idLan']}">Ajouter un tournoi</a></td>
	</tr>
</table>"
HTML
);
	}
	echo $page->toHTML();

}
else {
	header('Location: message.php?message=un problème est survenu');
}

function toString(Tournoi $tournoi,$i){
	$equipe = sizeof($tournoi->getEquipe())."/".$tournoi->getNbEquipeMax();
	$tour= <<<HTML

	<tr>
		<td>{$tournoi->getNomTournoi()}</td>
		<td>{$tournoi->getDateHeurePrevu()}</td>
		<td>{$tournoi->getTpElimination()}</td>
		<td>{$equipe}<a href="listeEquipeTournoi.php?idLan={$_GET['idLan']}&idTournoi={$i}">Voir les équipes</a></td>
		<td>{$tournoi->getNbPersMaxParEquipe()}</td>
		<td><a href="lancer.php?idLan={$_GET['idLan']}&idTournoi={$i}">Lancer le tournoi</a></td>
	</tr>
HTML;
	return $tour;
}