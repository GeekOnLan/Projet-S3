<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once ('includes/connectedMember.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Mes Lans");
$page->appendCssUrl("style/regular/listeLansMembre.css", "screen and (min-width: 680px");
$page->appendCssUrl("style/mobile/listeLans.css", "screen and (max-width: 680px");
$page->appendJsUrl("js/detailsLansMembre.js");

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

foreach($lans as $lan) {
	$html .= $lan->toString();
}

$html .= <<<HTML
    </table>
HTML;

$page->appendContent($html);

echo $page->toHTML();
