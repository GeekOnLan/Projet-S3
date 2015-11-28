<?php

require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');



if(isset($_REQUEST['idLan'])&&is_numeric($_REQUEST['idLan'])) {

    $page = new GeekOnLanWebpage("GeekOnLan - Modification de LAN");

    $page->appendCssUrl("style/regular/updateLan.css", "screen and (min-width: 680px");
    $page->appendCssUrl("style/mobile/updateLan.css", "screen and (max-width: 680px");
    $lans = Member::getInstance()->getLAN();
    if ($_REQUEST['idLan'] <= sizeof($lans) - 1) {

        $lan = $lans[$_REQUEST['idLan']];

    } else {
        $page->appendContent("<div>Lan inexistante</div>");
    }

    if(isset($_POST['nameLAN'])||isset($_POST['dateLAN'])||isset($_POST['descriptionLAN'])||isset($_POST['villeLAN'])||isset($_POST['adresseLAN'])){
        if(!empty($_POST['nameLAN']))
            $lan->setName($_POST['nameLAN']);
        if(!empty($_POST['dateLAN']))
            $lan->setDate($_POST['dateLAN']);
        if(!empty($_POST['descriptionLAN']))
            $lan->setDescription($_POST['descriptionLAN']);
        if(!empty($_POST['villeLAN']))
            $lan->setLieux($_POST['villeLAN']);
        if(!empty($_POST['adresseLAN']))
            $lan->setAdress($_POST['adresseLAN']);
        $page->appendContent("<p>Votre LAN a bien ete modifiee.</p>");
    }
    else {
        $page->appendContent(formulaire($lan));
        $page->appendJsUrl("js/creeLan.js");
    }
    echo $page->toHTML();

}

else {
        echo "redirection";
}


function formulaire($lan)
{
    $form = <<<HTML

<form method="POST" name="modifLAN" action="updateLAN.php?idLan={$lan->getId()}">
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
                    <input name="nameLAN" type="text"  placeholder="Nom" onfocus="resetNameLAN()" onblur="verifyNameLAN()">
                </div>
                <span id="erreurNameLAN"></span>
            </td>
             <td>
                <label for="villeLAN">Ville*</label>
                <div>
                    <img id="ville" src="resources/img/Ville.png"/>
                    <input name="villeLAN" type="text" placeholder="Ville" onfocus="resetVilleLAN()" onblur="verifyVilleLAN()">
                </div>
                <span id="erreurVilleLAN"></span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="detaLAN">Date de lévènement*</label>
                <div>
                    <img src="resources/img/Birthday.png"/>
                    <input name="dateLAN" placeholder="Date" onfocus="resetDateLAN()" onblur="verifyDateLAN()" type="text">
                </div>
                <span id="erreurDateLAN"></span>
            </td>
             <td>
                <label for="adresseLAN">Adresse*</label>
                <div>
                    <img id="ville" src="resources/img/Ville.png"/>
                    <input name="adresseLAN" type="text" placeholder="Adresse" onfocus="resetAdresseLAN()" onblur="verifyAdresseLAN()">
                </div>
                <span id="erreurAdresseLAN"></span>
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
                    <textarea maxlength="90" name="descriptionLAN" type="text" onfocus="resetDescriptionLAN" onblur="verifyDescriptionLAN()"></textarea>
                </div>
                <span id="erreurDescriptionLAN"> </span>
            </td>
        </tr>
    </table>
    <a href="manageTournament.php?idLan={$lan->getId()}">Gerer les Tournois</a>
    <button type="submit">Modifier la Lan</button>
    <a href="delLan.php?idLan={$lan->getId()}">Supprimer la Lan</a>
    Je passe le numero dans en get, c'est pas cool

    <p>* : champs obligatoires</p>
</form>

HTML;
    return $form;
}
