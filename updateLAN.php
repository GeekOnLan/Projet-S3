<?php

require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');



if(isset($_REQUEST['idLan'])&&is_numeric($_REQUEST['idLan'])) {

    $page = new GeekOnLanWebpage("GeekOnLan - Modification de LAN");

    $page->appendCssUrl("style/regular/updateLan.css", "screen and (min-width: 680px");
    $page->appendCssUrl("style/mobile/updateLan.css", "screen and (max-width: 680px");
    $lans = Member::getInstance()->getLAN();
    $lan = null;
    if ($_REQUEST['idLan'] <= sizeof($lans) - 1) {
        $lan = $lans[$_REQUEST['idLan']];
    }else
        header('Location: message.php?message=un problème est survenu');
    
    if(isset($_POST['nameLAN'])||isset($_POST['dateLAN'])||isset($_POST['descriptionLAN'])||isset($_POST['villeLAN'])||isset($_POST['adresseLAN'])){
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
    }
    else {
        $prompt = <<<HTML
		<div id="myPrompt">
			<h2>Supprimer cette LAN ? ?</h2>
			<form id="formDelete" name="delete" method="POST" action="deleteLan.php?idLan={$_REQUEST['idLan']}">
				<button type="button" id="idConfirmer" value="Confirmer" >Confirmer</button>
				<button type="button" id="idAnnuler" value="Annuler">Annuler</button>
		 	</form>
		</div>
HTML;

        $page->appendForeground($prompt);
        $page->appendContent(formulaire($lan));
        $page->appendJsUrl("js/creeLan.js");
        $page->appendJsUrl("js/deleteLan.js");
        $page->appendJsUrl("js/updateLan.js");
    }
    echo $page->toHTML();

}

else {
    header('Location: message.php?message=un problème est survenu');
}


function formulaire($lan)
{
    $form = <<<HTML

<form method="POST" name="modifLAN" action="updateLAN.php?idLan={$_REQUEST['idLan']}">
    <h2>Modification</h2>
    <table>
        <tr>
    			<td colspan="2">
    				<h3>Information de la LAN</h3>
    				<hr/>
    			</td>
		</tr>
        <tr>
            <td>
                <label for="nameLan">Nom de la LAN*</label>
                <div>
                    <img id="lanName" src="resources/img/Lan.png"/>
                    <input name="nameLAN" type="text"  placeholder="Nom" value="{$lan->getLanName()}" onfocus="resetNameLAN()" onblur="verifyNameLANUpdate()">
                </div>
                <span id="erreurNameLAN"> </span>
            </td>
             <td>
                <label for="villeLAN">Ville*</label>
                <div>
                    <img id="ville" src="resources/img/Ville.png"/>
                    <input name="villeLAN" type="text" placeholder="Ville" value="{$lan->getLieu()->getSlug()}" onfocus="resetVilleLAN()" onblur="verifyVilleLAN()">
                </div>
                <span id="erreurVilleLAN"> </span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="dateLAN">Date de l'évènement*</label>
                <div>
                    <img src="resources/img/Birthday.png"/>
                    <input name="dateLAN" placeholder="Date" value="{$lan->getLanDate()}" onfocus="resetDateLAN()" onblur="verifyDateLAN()" type="text">
                </div>
                <span id="erreurDateLAN"> </span>
            </td>
             <td>
                <label for="adresseLAN">Adresse*</label>
                <div>
                    <img id="ville" src="resources/img/Ville.png"/>
                    <input name="adresseLAN" type="text" placeholder="Adresse" value="{$lan->getAdresse()}" onfocus="resetAdresseLAN()" onblur="verifyAdresseLAN()">
                </div>
                <span id="erreurAdresseLAN"> </span>
            </td>
        </tr>
        <tr>
    			<td colspan="2">
    				<h3>Information complémentaire</h3>
    				<hr/>
    			</td>
		</tr>
        <tr>
            <td colspan="2" id="area">
                <label for="descriptionLAN">Déscription de la LAN</label>
                <div>
                    <textarea maxlength="90" name="descriptionLAN" type="text" onfocus="resetDescriptionLAN" onblur="verifyDescriptionLAN()">{$lan->getLanDescription()}</textarea>
                </div>
                <span id="erreurDescriptionLAN"> </span>
            </td>
        </tr>
    </table>
    <input name="originalName" type="hidden" value="{$lan->getLanName()}">           
    <button type="button" onclick="verifyUpdate()">Modifier la Lan</button>
    <button type="button" id="buttonDelete">Supprimer la Lan</button>
    <p>* : champs obligatoires</p>
</form>
HTML;


    return $form;
}
