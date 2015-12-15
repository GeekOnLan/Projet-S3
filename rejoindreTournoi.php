<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once ('includes/connectedMember.inc.php');

if(verify($_GET, 'idLan') && verify($_GET, 'idTournoi')) {
	$tournoi=null;
	try{
		$tournoi = Tournoi::getTournoiFromLAN($_GET['idLan'],$_GET['idTournoi']);
	}
	catch(Exception $e){
		header('Location: message.php?message=un problème est survenu');
	}
	$form = new GeekOnLanWebpage("GeekOnLan - Rejoindre un tournoi");
	echo $form->toHTML();
}
else {
	header('Location: message.php?message=un problème est survenu');
}

function formulaire(){
	$html= <<<HTML

<form method="POST" name="ajoutLAN" action="creeLan.php">
    <h2>Crée une equipe</h2>
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
    <button type="button" onclick="verifyLAN()">Crée une Lan</button>
    <p>* : champs obligatoires</p>
</form>

HTML;
	return $html;

}