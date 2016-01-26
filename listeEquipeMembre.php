<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once ('includes/connectedMember.inc.php');

//on verifi si les parametre sont bon
if(isset($_GET['idLan'])&&is_numeric($_GET['idLan']) && isset($_GET['idTournoi'])&&is_numeric($_GET['idTournoi'])) {
	//on recupere le tournoi corespondant
	$lans = Member::getInstance()->getLAN();
	$lan = null;
	if ($_GET['idLan'] <= sizeof($lans) - 1)
		$lan = $lans[$_GET['idLan']];
	else
		header('Location: message.php?message=un problème est survenu');
	
	$tournois=$lan->getTournoi();
	$tournoi = null;
	if ($_GET['idTournoi'] <= sizeof($tournois) - 1)
		$tournoi = $tournois[$_GET['idTournoi']];
	else
		header('Location: message.php?message=un problème est survenu');

	//on cree la webPage
	$wp = new GeekOnLanWebpage("GeekOnLan - Equipes");
	$wp->appendCssUrl("style/regular/listeEquipeMembre.css", "screen and (min-width: 680px)");

	$html = "";

	//on recupere les equipes du tournoi
	$equipes = $tournoi->getEquipe();

	//si pas d'equipe
	if(sizeof($equipes)==0){
		$html .= <<<HTML
<div class="noEquipe">
	<p>Aucune equipe n'a rejoind votre Tournoi</p>
</div>
HTML;
	}
	else{
		//on parcour les equipes
		$i = 0;
		$html = "<div class='listeEquipe'>";
		foreach($equipes as $equipe){
			$createur = $equipe->getCreateur();
			$createurSpan = "<span class='createur'>Createur : ".$createur->getPseudo()."</span><br>";
			$membres = "";

			//gestion de chaque membre de l'equipe
			foreach($equipe->getMembre() as $membre) {
				$i++;
				if ($createur->getId() != $membre->getId()) {
					$membres .= "<span class='membre'>" . $membre->getPseudo() . "</span><button type='button' id='boutonExclure{$i}'>Exclure</button><br>";

					//ajout du menue d'exclusion du membre en forground
					$wp->appendForeground(<<<HTML
<div id="exclure{$i}">
		<h2>Exclure {$membre->getPseudo()} ? ?</h2>
		<form id="formExclure" name="exclureMembre{$i}" method="POST" action="deleteEquipeMembre.php?idEquipe={$equipe->getIdEquipe()}&&idMembre={$membre->getId()}">
			<button type="button" id="idConfirme{$i}" value="Confirme{$i}" >Confirmer</button>
			<button type="button" id="idAnnule{$i}" value="Annule{$i}">Annuler</button>
		</form>
	</div>
HTML
					);

					//ajout du css particulier au membre pour l'exclusion
					$wp->appendToHead(<<<HTML
<style type="text/css">
	#exclure{$i}.open{$i} {
		transform: scale3d(1, 1, 1);
		-webkit-transform: scale3d(1, 1, 1);
		-moz-transform: scale3d(1, 1, 1);
	}

	#exclure{$i}.deleteLayer{$i} {
		visibility: visible;
		opacity: 0.5;
	}
</style>
HTML
					);

					//ajout du javascript particulier au membre pour l'excusion
					$wp->appendToHead(<<<HTML
<script type="text/javascript">
		$(function() {
			toggleLayer.actions.push({
				actionClass: "deleteLayer{$i}",
				doAction: toggleDelete{$i}
			});
			$("#idAnnule{$i}").click(toggleDelete{$i});
			document.getElementById("idConfirme{$i}").onclick = function(){
				document.exclureMembre{$i}.submit();
			}
			$("#boutonExclure{$i}").click(toggleDelete{$i});
		});

		var toggleDelete{$i} = function() {
			$("#exclure{$i}").toggleClass("open{$i}");
			$("body > div[id='layer']").toggleClass("deleteLayer{$i}");
			$("#layer").toggleClass("hid");
		};
</script>
HTML
					);
				}
			}
			//fin de la boucle pour les membre de l'equipe
			$i++;

			$html.=<<<HTML
<div class="equipeBlocks">
	<div class="title">
		<span>{$equipe->getNomEquipe()}</span>
		<hr>
	</div>
	<div class="info">
		<span>Description :</span>
		<span>{$equipe->getDescriptionEquipe()}</span><br>
		<button type="button" id="boutonSup{$i}">Exclure cette equipe</button>
	</div>
	<hr>
	<div class="membres">
		{$createurSpan}
		{$membres}
	</div>
</div>
HTML;
			//ajout du menue d'exclusion en forground
			$wp->appendForeground(<<<HTML
<div id="myPrompt{$i}">
		<h2>Exclure l'equipe {$equipe->getNomEquipe()} ? ?</h2>
		<form id="formDelete" name="deleteEquipe{$i}" method="POST" action="deleteEquipeMembre.php?idEquipe={$equipe->getIdEquipe()}">
			<button type="button" id="idConfirmer{$i}" value="Confirmer{$i}" >Confirmer</button>
			<button type="button" id="idAnnuler{$i}" value="Annuler{$i}">Annuler</button>
		</form>
	</div>
HTML
			);

			//ajout du css particulier a l'equipe pour l'exclusion
			$wp->appendToHead(<<<HTML
<style type="text/css">
	#myPrompt{$i}.open{$i} {
		transform: scale3d(1, 1, 1);
		-webkit-transform: scale3d(1, 1, 1);
		-moz-transform: scale3d(1, 1, 1);
	}

	#myPrompt{$i}.deleteLayer{$i} {
		visibility: visible;
		opacity: 0.5;
	}
</style>
HTML
			);

			//ajout du javascript particulier a l'equipe pour l'excusion
			$wp->appendToHead(<<<HTML
<script type="text/javascript">
		$(function() {
			toggleLayer.actions.push({
				actionClass: "deleteLayer{$i}",
				doAction: toggleDelete{$i}
			});
			$("#idAnnuler{$i}").click(toggleDelete{$i});
			document.getElementById("idConfirmer{$i}").onclick = function(){
				document.deleteEquipe{$i}.submit();
			}
			$("#boutonSup{$i}").click(toggleDelete{$i});
		});

		var toggleDelete{$i} = function() {
			$("#myPrompt{$i}").toggleClass("open{$i}");
			$("body > div[id='layer']").toggleClass("deleteLayer{$i}");
			$("#layer").toggleClass("hid");
		};
</script>
HTML
			);
		}
		$html .= "</div>";
	}

	$wp->appendContent($html);
	echo $wp->toHTML();
}
else {
	header('Location: message.php?message=un problème est survenu');
}