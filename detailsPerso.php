<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once ('includes/connectedMember.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Détails Lan");
$page->appendCssUrl("style/regular/details.css" , "screen and (min-width: 680px)");
$page->appendCssUrl("style/mobile/listeLans.css", "screen and (max-width: 680px");

$membre = Member::getInstance();

if(isset($_GET['idLan'])&&is_numeric($_GET['idLan'])){
	$membre = Member::getInstance();
	$lans = $membre->getLAN();
	if($_GET['idLan'] > sizeof($lans) - 1){
		//header('Location: message.php?message=un problème est survenu');
		echo "ici";
	}
	else{
		$lan=$lans[$_GET['idLan']];
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
		<td><a href="updateLAN.php?idLan={$_GET['idLan']}">Modifier</a></td>
		<td><a href="listeTournoisMembre.php?idLan={$_GET['idLan']}">Mes Tournois</a></td>
	</tr>
		
HTML;
		$page->appendContent($html."</table>");
		echo $page->toHTML();
	}
} 
else
	header('Location: message.php?message=un problème est survenu');