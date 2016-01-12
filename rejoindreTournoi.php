<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once ('includes/connectedMember.inc.php');

if(isset($_GET['idLan']) && isset($_GET['idTournoi']) && is_numeric($_GET['idLan']) && is_numeric($_GET['idTournoi']) && verify($_POST, 'nameEquipe')) {
	if(!verify($_POST, 'descriptionEquipe'))
		$_POST['descriptionEquipe']="";
    Equipe::createEquipe($_GET['idLan'],$_GET['idTournoi'],$_POST['nameEquipe'],$_POST['descriptionEquipe']);
    //header('Location: message.php?message=votre equipe a bien été crée');
}
else if(isset($_GET['idLan']) && isset($_GET['idTournoi']) && is_numeric($_GET['idLan']) && is_numeric($_GET['idTournoi'])){
    $tournoi=null;
	try{
		$tournoi = Tournoi::getTournoiFromLAN($_GET['idLan'],$_GET['idTournoi']);
	}
	catch(Exception $e){
		header('Location: message.php?message=un problème est survenu');
	}
	$form = new GeekOnLanWebpage("GeekOnLan - Rejoindre un tournoi");
    $form->appendCssUrl("style/regular/rejoindreTournoi.css", "screen and (min-width: 680px");
    $form->appendJsUrl("js/creeEquipe.js");
    $form->appendContent(formulaire());
    $form->appendContent(creeEquipe());
	echo $form->toHTML();
}
else{
	header('Location: message.php?message=un problème est survenu');
}

function equipe(){
    $equipe = Tournoi::getTournoiFromLAN($_GET['idLan'],$_GET['idTournoi'])->getEquipe();
    foreach($equipe as $e)
        echo $e->getIdEquipe();
}

function creeEquipe(){
    $html= <<<HTML

<form method="POST" name="creeEquipeForm" action="rejoindreTournoi.php?idLan={$_GET['idLan']}&idTournoi={$_GET['idTournoi']}">
    <table class="creeEquipeForm">
        <thead>
            <tr>
                <th colspan="2">
                    <h2>Créer une equipe</h2>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <label for="nameEquipe">Nom de l`equipe *</label>
                    <div class="formInput">
                        <img id="nameEquipe" src="resources/img/Lan.png"/>
                        <input maxlength="31" name="nameEquipe" type="text"  placeholder="Nom">
                    </div>
                    <span id="erreurNameLAN"></span>
                </td>
				<td>
                    <div class="formInput">
                        <label><input type="radio" name="ouvert" value="true" checked>Inscription ouverte</label>
                        <label><input type="radio" name="ouvert" value="false">Inscription fermée</label>
                    </div>
                </td>
                <td rowspan="4" id="area">
                    <label for="descriptionEquipe">Description de l'equipe</label>
                    <div class="formTextarea">
                        <textarea maxlength="255" name="descriptionEquipe"></textarea>
                    </div>
                    <span id="erreurDescriptionEquipe"></span>
                </td>
            </tr>
            <tr>
                <td><button type="button" onclick="creeEquipe()" >Continuer</button></td>
                <td><p>* : Champs obligatoires</p></td>
            </tr>
        </tbody>
    </table>
</form>

HTML;
    return $html;
}

function formulaire(){
	$html= <<<HTML

<form method="POST" name="equipe">
    <table class="equipeBox">
        <thead>
            <tr>
                <th>
                    <h2>Rejoindre un  tournoi</h2>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="formInput">
                        <label><input type="radio" name="sex" value="male" checked>Rejoindre une equipe</label>
                        <label><input type="radio" name="sex" value="female">Cree une equipe</label>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</form>

HTML;
	return $html;
}