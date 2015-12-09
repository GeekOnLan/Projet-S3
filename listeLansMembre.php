<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once ('includes/connectedMember.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Mes Lans");
$page->appendCssUrl("style/regular/listeLansMembre.css", "screen and (min-width: 680px");
$page->appendCssUrl("style/mobile/listeLans.css", "screen and (max-width: 680px");

$membre = Member::getInstance();
$lans = $membre->getLAN();

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
	$html .= $lan->toString();
	$html.="<td><a href=\"updateLAN.php?idLan=".$i."\">Modifier</a></td></tr>";
	$html.="<td><a href=\"tournoialacon.php?idLan=".$i."\">Mes Tournois</a></td></tr>";
	$i++;
}

$html .= <<<HTML
	</table>
HTML;

	

$page->appendContent($html);

echo $page->toHTML();
