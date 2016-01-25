<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once ('includes/connectedMember.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Mes Lans");
$page->appendCssUrl("style/regular/listeLansMembre.css", "screen and (min-width: 680px)");

/*recuperation des LAN de l'utilisateur*/

$membre = Member::getInstance();
$lans = $membre->getLAN();
$html = "<div class='listeLans'>";

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

$i = 0;
/*parcour de toute les LAN*/
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
        	<a href="listeTournoiMembre.php?idLan=$i">Tournois</a>
        	<button type="button" id="bouttonDetails{$i}">Détails</button>
        	<a href="updateLAN.php?idLan=$i">Editer</a>
        	<a href="creeTournoi.php?idLan={$i}">Ajouter un tournoi</a>
		</div>
	</div>
HTML;

	$page->appendToHead(<<<HTML
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

		#layer.hid{$i} {
			 visibility: visible;
			 opacity: 0.5;
 		}

	</style>
HTML
	);

	$page->appendToHead(<<<HTML
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
			$("#layer").toggleClass("hid{$i}");
		};
	</script>
HTML
	);
	/*ajout des details de la LAN en forground*/
	$page->appendForeground(<<<HTML
<div id="details{$i}">
		<h2>{$lan->getLanName()}</h2>
		<div class="lanDetails">
			<span class="title">Description :</span><br>
			<span>{$lan->getLanDescription()}</span><br>
			<span class="title">Adresse :</span><br>
			<span>{$lan->getAdresse()}</span><br>
			<span>{$lan->getLieu()->getCodePostal()}</span>
			<span>{$lan->getLieu()->getNomVille()}</span>
			<button type="button" id="idFermee{$i}">Fermer</button>
		</div>
	</div>
HTML
);
	$i++;
}
/*si pas de LAN*/
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
