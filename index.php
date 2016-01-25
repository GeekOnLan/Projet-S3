<?php

require_once('includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Accueil");
$webpage->appendCssUrl("style/regular/accueil.css", "screen and (min-width: 680px)");

$lesLans = Lan::getRecentLan();
$news = "";

// Pour les mois écrit en français
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

$i = 0;
foreach($lesLans as $lan){
    $img = $lan->getLanPicture();

    // Si il n'y a pas d'image, on en met une part défaut
    if($img == null)
        $img = "resources/img/logo.png";

    // La date avec des '/' c'est moche, donc on décompose la date pour faire un joli
    // style en CSS
    $date = explode('/', $lan->getLanDate());
    $day = $date[0];
    $month = ucfirst(strftime('%B', mktime(0, 0, 0, $date[1])));

    $news.=<<<HTML
        <table class="news">
            <tr>
                <td>
                    <div>
                        <span>$day</span>
                        <span>$month</span>
                    </div>
                    <img src="$img" alt="news" />
                </td>
                <td>
                    <div>
                        <h2>{$lan->getLanName()}</h2>
                        <hr/>
                        <p>{$lan->getLanDescription()}</p>
                        <a href="listeTournoi.php?idLan={$lan->getId()}">Tournoi</a>
                        <button type="button" id="bouttonDetails{$i}">Details</button>
                    </div>
                </td>
            </tr>
        </table>
HTML;

    $webpage->appendToHead(<<<HTML
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

    $webpage->appendToHead(<<<HTML
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
    $webpage->appendForeground(<<<HTML
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

$webpage -> appendContent($news);
echo $webpage->toHTML();
