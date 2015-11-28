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
    <h2>Modification de {$lan->getLanName()}</h2>
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
                    <input name="nameLAN" type="text"  placeholder="Nom"  value="{$lan->getLanName()}">
                </div>
                <span id="erreurNameLAN"></span>
            </td>
             <td>
                <label for="villeLAN">Ville*</label>
                <div>
                    <input name="villeLAN" type="text" placeholder="Ville"  >
                </div>
                <span id="erreurVilleLAN"></span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="detaLAN">Date de lévènement*</label>
                <div>
                    <input name="dateLAN" placeholder="Date"  type="text" value="{$lan->getLanDate()}">
                </div>
                <span id="erreurDateLAN"></span>
            </td>
             <td>
                <label for="adresseLAN">Adresse*</label>
                <div>
                    <input name="adresseLAN" type="text" placeholder="Adresse"  value="{$lan->getAdress()}">
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
                <label for="descriptionLAN">Description de la LAN</label>
                <div>
                    <textarea maxlength="90" name="descriptionLAN" type="text"> {$lan->getLanDescription()}</textarea>
                </div>
                <span id="erreurDescriptionLAN"></span>
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
