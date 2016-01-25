<?php

require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Modification de LAN");
$page->appendCssUrl("style/regular/updateLan.css", "screen and (min-width: 680px)");
$page->appendCssUrl("style/mobile/updateLan.css", "screen and (max-width: 680px)");
$page->appendJsUrl("js/creeLan.js");
$page->appendJsUrl("js/deleteLan.js");
$page->appendJsUrl("js/updateLan.js");

$lans = Member::getInstance()->getLAN();

// On redirige l'utilisateur si l'identifiant de la Lan est incorrecte
if(!isset($_GET["idLan"]) || !is_numeric($_GET["idLan"]) || $_GET["idLan"] >= sizeof($lans))
    header('Location: message.php?message=un problème est survenu');
$lan = $lans[$_GET["idLan"]];

// TODO Faudrait faire une vérification plus poussée ici (date, ville, etc ...)
if(isset($_POST['nameLAN']) || isset($_POST['dateLAN']) || isset($_POST['descriptionLAN']) || isset($_POST['villeLAN']) || isset($_POST['adresseLAN'])) {
        $nom=$lan->getLanName();
        $date=$lan->getLanDate();
        $desc=$lan->getLanDescription();
        $lieu=$lan->getLieu()->getSlug();
        $adresse=$lan->getAdress();
    	if(!empty($_POST['nameLAN']))
            $nom=$_POST['nameLAN'];
        if(!empty($_POST['dateLAN']))
            $date=$_POST['dateLAN'];
        if(!empty($_POST['descriptionLAN']))
            $desc=$_POST['descriptionLAN'];
        if(!empty($_POST['villeLAN']))
            $lieu=$_POST['villeLAN'];
        if(!empty($_POST['adresseLAN']))
            $adresse=$_POST['adresseLAN'];
        $lan->update($nom,$date,$desc,$lieu,$adresse);
        $page->appendContent("<p>Votre LAN a bien ete modifiee.</p>");
} else {
    $prompt = <<<HTML
		<div id="myPrompt">
			<h2>Supprimer cette LAN ? ?</h2>
			<form id="formDelete" name="delete" method="POST" action="deleteLan.php?idLan={$_REQUEST['idLan']}">
				<button type="button" id="idConfirmer" value="Confirmer" >Confirmer</button>
				<button type="button" id="idAnnuler" value="Annuler">Annuler</button>
		 	</form>
		</div>
HTML;

    $form = <<<HTML

<form method="POST" name="modifLAN" action="updateLAN.php?idLan={$_REQUEST['idLan']}">
    <table class="lanForm">
        <thead>
            <tr>
                <th colspan="2">
                    <h2>Edition de la Lan</h2>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <label for="nameLan">Nom de la LAN*</label>
                    <div class="formInput">
                        <img id="lanName" src="resources/img/Lan.png"/>
                        <input id="nameLan" name="nameLAN" type="text"  placeholder="Nom" value="{$lan->getLanName()}" onfocus="resetNameLAN()" onblur="verifyNameLANUpdate()">
                    </div>
                    <span id="erreurNameLAN"> </span>
                </td>
                <td rowspan="4" id="area">
                    <label for="descriptionLAN">Description de la LAN</label>
                    <div class="formTextarea">
                        <textarea id="descriptionLAN" maxlength="90" name="descriptionLAN" onfocus="resetDescriptionLAN" onblur="verifyDescriptionLAN()">{$lan->getLanDescription()}</textarea>
                    </div>
                    <span id="erreurDescriptionLAN"> </span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="villeLAN">Ville*</label>
                    <div class="formInput">
                        <img id="ville" src="resources/img/Ville.png"/>
                        <input id="villeLAN" name="villeLAN" type="text" placeholder="Ville" value="{$lan->getLieu()->getSlug()}" onfocus="resetVilleLAN()" onblur="verifyVilleLAN()">
                    </div>
                    <span id="erreurVilleLAN"> </span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="dateLAN">Date de l'évènement*</label>
                    <div class="formInput">
                        <img src="resources/img/Birthday.png"/>
                        <input id="dateLAN" name="dateLAN" placeholder="Date" value="{$lan->getLanDate()}" onfocus="resetDateLAN()" onblur="verifyDateLAN()" type="text">
                    </div>
                    <span id="erreurDateLAN"> </span>
                </td>
            </tr>
            <tr>
                 <td>
                    <label for="adresseLAN">Adresse*</label>
                    <div class="formInput">
                        <img id="ville" src="resources/img/Ville.png"/>
                        <input id="adresseLAN" name="adresseLAN" type="text" placeholder="Adresse" value="{$lan->getAdresse()}" onfocus="resetAdresseLAN()" onblur="verifyAdresseLAN()">
                    </div>
                    <span id="erreurAdresseLAN"> </span>
                </td>
            </tr>
            <tr>
                <td><button type="button" onclick="verifyUpdate()">Modifier la Lan</button></td>
                <td><button type="button" id="buttonDelete">Supprimer la Lan</button></td>
            </tr>
            <tr>
                <td colspan="2"><p>* : champs obligatoires</p></td>
            </tr>
        </tbody>
    </table>
    <input name="originalName" type="hidden" value="{$lan->getLanName()}">
</form>
HTML;

    $page->appendForeground($prompt);
    $page->appendContent($form);
}

echo $page->toHTML();
