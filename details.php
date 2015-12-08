<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once ('includes/connectedMember.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Détails Lan");
$page->appendCssUrl("style/regular/listeLansMembres.css" , "screen and (min-width: 680px)");
$page->appendCssUrl("style/mobile/listeLans.css", "screen and (max-width: 680px");

$membre = Member::getInstance();

if(verify($_GET,'idLan')){
	$lan = Lan::createFromId($_GET['idLan']);
	$open= "non";
	if($lan->isOpen()) $open = "oui";

	$html = <<<HTML

<table>
	<tr>
		<td>Nom</td>
		<td>Date</td>
		<td>Lieu</td>
		<td>Description</td>
		<td>Adresse</td>
		<td>Ouverte ?</td>
		<td>Nom Simple</td>
		<td>Ville</td>
		<td>Arrondissement</td>
		<td>Code Postal</td>
		<td>Departement</td>
		<td>Canton</td>
	</tr>
	<tr>
		<td>{$lan->getLanName()}</td>
   		<td>{$lan->getLanDate()}</td>
   		<td>{$lan->getLieux()->getNomSimple()}</td>
		<td>{$lan->getLanDescription()}</td>
		<td>{$lan->getAdress()}</td>
		<td>{$open}</td>
		<td>{$lan->getLieux()->getNomSimple()}</td>
		<td>{$lan->getLieux()->getNomVille()}</td>
		<td>{$lan->getLieux()->getArrondissement()}</td>
		<td>{$lan->getLieux()->getCodePostal()}</td>
		<td>{$lan->getLieux()->getDepartement()}</td>
		<td>{$lan->getLieux()->getCanton()}</td>
	</tr>
	<tr>
		<td><a href=\"updateLAN.php?idLan="{$_GET['idLan']}"">Modifier</a></td>
		<td><a href=\"tournoialacon.php?idLan="{$_GET['idLan']}"">Mes Tournois</a></td>
	</tr>
		
HTML;

	$page->appendContent($html);
	echo $page->toHTML();
} else
	header('Location: message.php?message=un problème est survenu');
