<?php
require_once('includes/autoload.inc.php');


if(isset($_REQUEST['idLan'])&&is_numeric($_REQUEST['idLan'])){
$connecte = Member::isConnected();
	$lan=null;
  try{
    $lan= Lan::createFromId($_REQUEST['idLan']);
  }catch(Exception $e){
    	header('Location: message.php?message=Lan inexistante');
  }
    $page = new GeekOnLanWebpage("GeekOnLan - ".$lan->getLanName()." - Liste des tournois");
    $page->appendCssUrl("style/regular/listeTournois.css", "screen and (min-width: 680px");
    $page->appendJsUrl("js/request.js");

    $tournois=$lan->getTournoi();

    if(sizeof($tournois)==0){
        $page->appendContent(<<<HTML
              <table id="{$_REQUEST['idLan']}">
              <tr>
                <th>Aucun tournoi prévu pour cette LAN</th>
              </tr>

              </table>
HTML
        );
    }
    else{
        $pdo = MyPDO::getInstance();
        $stmt = $pdo->prepare(<<<SQL
			SELECT idMembre, idEquipe
			FROM Composer
			WHERE idEquipe=(
    			SELECT idEquipe
    			FROM Participer
    			WHERE idLAN=:idLan
    			AND idTournoi=:idTournoi
    		);
SQL
        );

        $html = "<div class='listeTournois'>";

        $i=0;
        foreach($tournois as $tournoi) {
            $button = "";
            if($connecte){
                try{
                    $stmt->execute(array("idLan" => $_GET['idLan'],"idTournoi" => $tournoi->getIdTournoi()));
                }
                catch(Exception $e){
                    header('Location: message.php?message=Un probleme est survenu');
                }
                $bool=TRUE;
                $res=$stmt->fetchAll();
                if(sizeof($res)!=0)
                    foreach ($res as $membre)
                        if($membre['idMembre']==Member::getInstance()->getId())
                            $bool=FALSE;
                if(!$bool)
                    $button = <<<HTML
<a href="gererEquipe.php?idEquipe={$membre['idEquipe']}" class="bouton">Gérer</a>
HTML;
                else{
                    if(!$tournoi->isFull())
                        $button = '<a href="rejoindreTournoi.php?idLan='.$_REQUEST['idLan'].'&idTournoi='.$tournoi->getIdTournoi().'" class = "bouton">Participer</a>';
                    else
                        $button = '<span>Plein</span>';
                }
            }

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
        	<button type="button" id="bouttonDetails{$i}">Détails</button>
        	{$button}
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
		<div class="tournoiDetails">
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

        $page->appendContent($html);
    }

    echo $page->toHTML();
}
else{
  header('Location: message.php?message=Un probleme est survenu');
}
