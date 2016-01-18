<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once ('includes/connectedMember.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Mes Lans");
$page->appendCssUrl("style/regular/listeLansMembre.css", "screen and (min-width: 680px");

$membre = Member::getInstance();
$lans = $membre->getLAN();
$html = "<div class='listeLans'>";

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

$i = 0;
foreach($lans as $lan) {
	$date = explode('/', $lan->getLanDate());
	$day = $date[0];
	$month = ucfirst(strftime('%B', mktime(0, 0, 0, $date[1])));
	$lieu = ucfirst(strtolower($lan->getLieu()->getNomVille()));

	$html .= <<<HTML
	<div class="lanBlocks">
		<span>A $lieu</span>
		<div class="lanDate">
        	<span>$day</span>
            <span>$month</span>
        </div>
        <div class="lanInfo">
        	<span>{$lan->getLanName()}</span>
        	<hr/>
        	<a href="updateLAN.php?idLan=$i">Editer</a>
        	<a href="listeTournoisMembre.php?idLan=$i">Tournois</a>
        	<a href="detailsLanPerso.php?idLan=$i">Détails</a>
		</div>
	</div>
HTML;
	$i++;
}

if(count($lans) == 0) {
	$html .= <<<HTML
	<div class="noLan">
		<p>Vous n'avez pas encore créé de LAN</p>
		<a href="creeLan.php">Créez en une !</a>
	</div>
HTML;

}

$html .= "</div>";

$page->appendContent($html);

echo $page->toHTML();
