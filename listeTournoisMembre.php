<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once ('includes/connectedMember.inc.php');

if(isset($_GET['idLan'])&&is_numeric($_GET['idLan'])) {
	setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
	
	$lans = Member::getInstance()->getLAN();
	$lan = null;
	if ($_GET['idLan'] <= sizeof($lans) - 1) {
		$lan = $lans[$_GET['idLan']];
	}else
		header('Location: message.php?message=un problème est survenu');

	$tournois=$lan->getTournoi();
	$page = new GeekOnLanWebpage("GeekOnLan - Tournois");
	$page->appendCssUrl("style/regular/listeTournoisMembre.css", "screen and (min-width: 680px");
	
	$html = "<div class='listeTournois'>";
	
	$i=0;
	foreach($tournois as $tournoi) {
		$date = explode('/', $tournoi->getDateHeurePrevu());
		$day = $date[0];
		$month = ucfirst(strftime('%B', mktime(0, 0, 0, $date[1])));
		
		$hour = explode('a ', $date[2]);
		$hour = $hour[1];
		
		$html .= <<<HTML
	<div class="tournoiBlocks">
		<div class="tournoiDate">
        	<span>$hour</span>
        </div>
        <div class="tournoiInfo">
        	<span>{$tournoi->getNomTournoi()}</span>
        	<hr/>
        	<a>Editer</a>
        	<a>Details</a>
        	<a href="listeEquipeTournoi.php?idLan={$_GET['idLan']}&idTournoi={$i}">Equipe</a>
        	<a href="lancer.php?idLan={$_GET['idLan']}&idTournoi={$i}">Lancer le tournoi</a>
		</div>
	</div>
HTML;
		$i++;
	}
	
	$html .= "</div>";
	

	$html.=<<<HTML
	<div class="ajout">
		<a href="creeTournoi.php?idLan={$_GET['idLan']}">Ajouter un tournoi</a>
	</div>
HTML;
	
	$page->appendContent($html);
	
	echo $page->toHTML();

}
else {
	header('Location: message.php?message=un problème est survenu');
}