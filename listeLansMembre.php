<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once ('includes/connectedMember.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Mes Lans");
$page->appendCssUrl("style/regular/listeLansMembre.css", "screen and (min-width: 680px");
$page->appendCssUrl("style/mobile/listeLans.css", "screen and (max-width: 680px");

$membre = Member::getInstance();
$lans = $membre->getLAN();
$html="";
if(sizeof($lans)==0){
	$html=<<<HTML
<table>
	<tr>
		<th>aucune LAN crée</th>
	</tr>
	<tr>
		<td><a href="creeLan.php">Crée une LAN</a></td>
	</tr>
</table>
HTML;
	
}
else{
	$html = <<<HTML
	
	<table>
		<tr>
			<td>Nom</td>
			<td>Date</td>
			<td>Lieu</td>
			<td>Description</td>
		</tr>
HTML;
	$i=0;
	foreach($lans as $lan) {
		$html .= $lan;
		$html.="<td><a href=\"updateLAN.php?idLan=".$i."\">Modifier</a></td></tr>";
		$html.="<td><a href=\"listeTournoisMembre.php?idLan=".$i."\">Mes Tournois</a></td></tr>";
		$html.="<td><a href=\"details.php?idLan=".$i."\">Détails</a></td>";
		$i++;
	}
	
	$html .= <<<HTML
		</table>
HTML;
}
	

$page->appendContent($html);

echo $page->toHTML();
