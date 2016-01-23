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
		$nbEquipe = sizeof($tournoi->getEquipe());
		$payant = "";
		if($tournoi->getJeu()[3]==0)
			$payant = "payant";
		else $payant = "gratuit";
		$html .= <<<HTML
	<div class="tournoiBlocks">
		<span>{$tournoi->getNomTournoi()}</span>
		<div class="tournoiDate">
			<span>{$day}</span>
			<span>{$month}</span>
        </div>
        <div class="tournoiInfo">
        	<span>$hour</span>
        	<hr/>
        	<a href="listeEquipeMembre.php?idLan={$_GET['idLan']}&idTournoi={$i}">Equipe</a>
        	<button type="button" id="bouttonDetails{$i}">Détails</button>
        	<a>Editer</a>
        	<a href="lancer.php?idLan={$_GET['idLan']}&idTournoi={$i}">Lancer le tournoi</a>
		</div>
	</div>

	<style>
		#details{$i}.open{$i} {
			transform: scale3d(1, 1, 1);
			-webkit-transform: scale3d(1, 1, 1);
			-moz-transform: scale3d(1, 1, 1);
		}

		#details{$i}.deleteLayer{$i} {
			visibility: visible;
			opacity: 0.5;
		}
	</style>

	<script type="text/javascript">
		$(function() {
			toggleLayer.actions.push({
				actionClass: "deleteLayer{$i}",
				doAction: toggleDelete{$i}
			});
			$("#idFermee{$i}").click(toggleDelete{$i});
			$("#bouttonDetails{$i}").click(toggleDelete{$i});
		});

		var toggleDelete{$i} = function() {
			$("#details{$i}").toggleClass("open{$i}");
			$("body > div[id='layer']").toggleClass("deleteLayer{$i}");
			$("#layer").toggleClass("hid");
		};
	</script>
HTML;
		$page->appendForeground(<<<HTML
<div id="details{$i}">
		<h2>{$tournoi->getNomTournoi()}</h2>
		<div class="lanDetails">
			<span class="title">Description :</span><br>
			<span>{$tournoi->getDescriptionTournoi()}</span><br>
			<span class="title">Jeu :</span><br>
			<span>{$tournoi->getJeu()[1]} : {$payant}</span><br>
			<span>{$tournoi->getJeu()[2]}</span><br>
			<span class="title">Equipe :</span><br>
			<span>Nombre d'equipe : {$nbEquipe}/{$tournoi->getNbEquipeMax()}</span><br>
			<span>Nombre de personne par equipe : {$tournoi->getNbPersMaxParEquipe()}</span><br>
			<button type="button" id="idFermee{$i}">Fermer</button>
		</div>
	</div>
HTML
);
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