<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Détails Lan");
$page->appendCssUrl("style/regular/detailsLan.css" , "screen and (min-width: 680px)");
$page->appendCssUrl("style/mobile/detailsLan.css", "screen and (max-width: 680px");

$membre = Member::getInstance();

if(isset($_GET['idLan'])&&is_numeric($_GET['idLan'])){
		$lan=Lan::createFromId($_GET['idLan']);
		$open= "Non";
		if($lan->isOpen()) $open = "Oui";

		$html = <<<HTML

<table id="details">
	<tr>
		<th>Nom</th>
		<th>Date</th>
		<th>Description</th>
		<th>Adresse</th>
		<th>Ouverte ?</th>
		<th>Ville</th>
		<th>Arrondissement</th>
		<th>Code Postal</th>
		<th>Departement</th>
	</tr>
	<tr>
		<td>{$lan->getLanName()}</td>
   		<td>{$lan->getLanDate()}</td>
		<td>{$lan->getLanDescription()}</td>
		<td>{$lan->getAdresse()}</td>
		<td>{$open}</td>
		<td>{$lan->getLieu()->getNomVille()}</td>
		<td>{$lan->getLieu()->getArrondissement()}</td>
		<td>{$lan->getLieu()->getCodePostal()}</td>
		<td>{$lan->getLieu()->getDepartement()}</td>
	</tr>
	<tr>
		<td></td><td></td><td></td><td></td>
		<td><a href="listeTournoi.php?idLan={$_GET['idLan']}">Tournois</a></td>
	</tr>

HTML;
		$page->appendContent($html."</table>");
		echo $page->toHTML();
}
else
	header('Location: message.php?message=un problème est survenu');