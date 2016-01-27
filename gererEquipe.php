<?php

require_once('includes/autoload.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/requestUtils.inc.php');
require_once('includes/connectedMember.inc.php');


//recuperation du membre
$membre = Member::getInstance();

//verification des information pour l'equipe
if(!isset($_GET['idEquipe']) || !is_numeric($_GET['idEquipe']))
    header('Location: message.php?message=Un problème est survenu');

//creation de l'equipe
$equipe = null;
try{
    $equipe = Equipe::createFromId($_GET['idEquipe']);
}
catch (Exception $e){
    header('Location: message.php?message=Un problème est survenu');
}

//on verifi que le membre fait bien parti de l'equipe
if(!$equipe->isInEquipe($membre->getId()))
    header('Location: message.php?message=Vous ne faite pas partie de cette équipe');

//on cree la webpage
$webPage = new GeekOnLanWebpage("GeekOnLan - gestion de l'equipe");
$webPage->appendCssUrl("style/regular/gererEquipe.css", "screen and (min-width: 680px)");
$webPage->appendJsUrl("js/inviteMember.js");

//on regarde si le membre est le createur
if($equipe->getCreateur()->getId() == $membre->getId()) {

    $html = "<div class='equipe'>";
    $createur = $equipe->getCreateur();
    $createurSpan = "<span class='createur'>Createur : " . $createur->getPseudo() . "</span><br>";
    $membres = "";

    //gestion de chaque membre de l'equipe
    $i = 0;
    foreach ($equipe->getMembre() as $membre) {
        $i++;
        if ($createur->getId() != $membre->getId()) {
            $membres .= "<span class='membre'>" . $membre->getPseudo() . "</span><button type='button' id='boutonExclure{$i}'>Exclure</button><br>";

            //ajout du menue d'exclusion du membre en forground
            $webPage->appendForeground(<<<HTML
<div id="exclure{$i}">
		<h2>Exclure {$membre->getPseudo()} ? ?</h2>
		<form id="formExclure{$i}" name="exclureMembre{$i}" method="POST" action="deleteEquipe.php?idEquipe={$equipe->getIdEquipe()}&&idMembre={$membre->getId()}">
			<button type="button" id="idConfirme{$i}" value="Confirme" >Confirmer</button>
			<button type="button" id="idAnnule{$i}" value="Annule">Annuler</button>
		</form>
	</div>
HTML
            );

            //ajout du css particulier au membre pour l'exclusion
            $webPage->appendToHead(<<<HTML
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
            $webPage->appendToHead(<<<HTML
<script type="text/javascript">
		$(function() {
			toggleLayer.actions.push({
				actionClass: "deleteLayer",
				doAction: toggleDelete
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

    $html .= <<<HTML
<div class="equipeBlocks">
	<div class="title">
		<span>{$equipe->getNomEquipe()}</span>
		<hr>
	</div>
	<div class="info">
		<span>Description :</span>
		<span>{$equipe->getDescriptionEquipe()}</span><br>
		<button type="button" id="boutonSup">Supprimer votre equipe</button>
		<button type="button" id="boutonInvite">Inviter un membre</button>
	</div>
	<hr>
	<div class="membres">
		{$createurSpan}
		{$membres}
	</div>
</div>
HTML;
    //ajout du menue d'exclusion en forground
    $webPage->appendForeground(<<<HTML
<div id="myPrompt">
		<h2>supprimer votre equipe ? ?</h2>
		<form id="formDelete" name="deleteEquipe" method="POST" action="deleteEquipe.php?idEquipe={$equipe->getIdEquipe()}">
			<button type="button" id="idConfirmer" value="Confirmer" >Confirmer</button>
			<button type="button" id="idAnnuler" value="Annuler">Annuler</button>
		</form>
</div>
<div id="myPromptInvite">
		<h2>Inviter un membre</h2>
		<form id="formInvite" name="formInvite" method="POST" action="inviteMember.php?idEquipe={$equipe->getIdEquipe()}">
		    <span id="erreurpseudo"></span>
			<div>
    			<img id="pseudoLogo" src="resources/img/Contact.png" alt="login" />
    		    <input id="pseudo" name="pseudo" type="text" placeholder="Pseudo" maxlength="31" onfocus="resetPseudo()" onblur="verifiPseudo()">
    		</div>
			<button type="button" id="idInviter" value="inviter" >Inviter</button>
			<button type="button" id="idRefuser" value="refuser">Annuler</button>
		</form>
</div>
HTML
    );

    //ajout du css particulier a l'equipe pour l'exclusion
    $webPage->appendToHead(<<<HTML
<style type="text/css">
	#myPrompt.open {
		transform: scale3d(1, 1, 1);
		-webkit-transform: scale3d(1, 1, 1);
		-moz-transform: scale3d(1, 1, 1);
	}

	#myPrompt.deleteLayer {
		visibility: visible;
		opacity: 0.5;
	}
	#myPromptInvite.openInviter {
		transform: scale3d(1, 1, 1);
		-webkit-transform: scale3d(1, 1, 1);
		-moz-transform: scale3d(1, 1, 1);
	}

	#myPromptInvite.deleteLayerInviter {
		visibility: visible;
		opacity: 0.5;
	}
</style>
HTML
    );

    //ajout du javascript particulier a l'equipe pour l'excusion
    $webPage->appendToHead(<<<HTML
<script type="text/javascript">
		$(function() {
			toggleLayer.actions.push({
				actionClass: "deleteLayer",
				doAction: toggleDelete
			});
			$("#idAnnuler").click(toggleDelete);
			document.getElementById("idConfirmer").onclick = function(){
				document.deleteEquipe.submit();
			}
			$("#boutonSup").click(toggleDelete);

			toggleLayer.actions.push({
				actionClass: "deleteLayerInvier",
				doAction: toggleDeleteInviter
			});
			$("#idRefuser").click(toggleDeleteInviter);
			document.getElementById("idInviter").onclick = function(){
				envoyerInvite();
			}
			$("#boutonInvite").click(toggleDeleteInviter);
		});

		var toggleDelete = function() {
			$("#myPrompt").toggleClass("open");
			$("body > div[id='layer']").toggleClass("deleteLayer");
			$("#layer").toggleClass("hid");
		};

		var toggleDeleteInviter = function() {
			$("#myPromptInvite").toggleClass("openInviter");
			$("body > div[id='layer']").toggleClass("deleteLayerInviter");
			$("#layer").toggleClass("hid");
		};
</script>
HTML
    );
    $html .= "</div>";
    $webPage->appendContent($html);
}
//sinon le membre ne peut juste que partir de l'equipe
else{

    $html = "<div class='equipe'>";
    $createur = $equipe->getCreateur();
    $createurSpan = "<span class='createur'>Createur : " . $createur->getPseudo() . "</span><br>";
    $membres = "";

    //gestion de chaque membre de l'equipe
    foreach ($equipe->getMembre() as $membre) {
        if ($createur->getId() != $membre->getId() && $membre->getId() == Member::getInstance()->getId()) {
            $membres .= "<span class='membre'>" . $membre->getPseudo() . "</span><button type='button' id='boutonExclure'>Partir</button><br>";

            //ajout du menue d'exclusion du membre en forground
            $webPage->appendForeground(<<<HTML
<div id="exclure">
		<h2>Voulez vous partir ? ?</h2>
		<form id="formExclure" name="exclureMembre" method="POST" action="partir.php?idEquipe={$equipe->getIdEquipe()}">
			<button type="button" id="idConfirme" value="Confirme" >Confirmer</button>
			<button type="button" id="idAnnule" value="Annule">Annuler</button>
		</form>
	</div>
HTML
            );

            //ajout du css particulier au membre pour l'exclusion
            $webPage->appendToHead(<<<HTML
<style type="text/css">
	#exclure.open {
		transform: scale3d(1, 1, 1);
		-webkit-transform: scale3d(1, 1, 1);
		-moz-transform: scale3d(1, 1, 1);
	}

	#exclure.deleteLayer {
		visibility: visible;
		opacity: 0.5;
	}
</style>
HTML
            );

            //ajout du javascript particulier au membre pour l'excusion
            $webPage->appendToHead(<<<HTML
<script type="text/javascript">
		$(function() {
			toggleLayer.actions.push({
				actionClass: "deleteLayer",
				doAction: toggleDelete
			});
			$("#idAnnule").click(toggleDelete);
			document.getElementById("idConfirme").onclick = function(){
				document.exclureMembre.submit();
			}
			$("#boutonExclure").click(toggleDelete);
		});

		var toggleDelete = function() {
			$("#exclure").toggleClass("open");
			$("body > div[id='layer']").toggleClass("deleteLayer");
			$("#layer").toggleClass("hid");
		};
</script>
HTML
            );
        }
        elseif($createur->getId() != $membre->getId())
            $membres .= "<span class='membre'>" . $membre->getPseudo() . "</span><br>";
    }
    //fin de la boucle pour les membre de l'equipe

    $html .= <<<HTML
<div class="equipeBlocks">
	<div class="title">
		<span>{$equipe->getNomEquipe()}</span>
		<hr>
	</div>
	<div class="info">
		<span>Description :</span>
		<span>{$equipe->getDescriptionEquipe()}</span><br>
		<button type="button" id="boutonInvite">Inviter un membre</button>
	</div>
	<hr>
	<div class="membres">
		{$createurSpan}
		{$membres}
	</div>
</div>
HTML;
    $html .= "</div>";
    $webPage->appendContent($html);
}

echo $webPage->toHTML();