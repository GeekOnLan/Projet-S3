<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once ('includes/connectedMember.inc.php');

if(isset($_GET['idLan']) && isset($_GET['idTournoi']) && is_numeric($_GET['idLan']) && is_numeric($_GET['idTournoi']) && Member::getInstance()->isInEquipeTournoi($_GET['idLan'], $_GET['idTournoi'])){
	header('Location: message.php?message=Vous êtes déjà inscrit dans ce tournoi');
}

if(isset($_GET['idLan']) && isset($_GET['idTournoi']) && is_numeric($_GET['idLan']) && is_numeric($_GET['idTournoi']) && verify($_POST, 'rejoindre')) {
	try{
		Equipe::createFromId($_POST['rejoindre'])->rejoindre(Member::getInstance()->getId());
	}
	catch(Exception $e){
		header('Location: message.php?message=Vous êtes déjà dans cette équipe');
	}
	header("Location: message.php?message=Vous avez bien intégré l'équipe !");
}
else if(isset($_GET['idLan']) && isset($_GET['idTournoi']) && is_numeric($_GET['idLan']) && is_numeric($_GET['idTournoi']) && verify($_POST, 'nameEquipe')) {
	if(!verify($_POST, 'descriptionEquipe'))
		$_POST['descriptionEquipe']="";
	
	if($_POST['ouvert']=="true")
		$_POST['ouvert']=0;
	else 
		$_POST['ouvert']=1;
	
    Equipe::createEquipe($_GET['idLan'],$_GET['idTournoi'],$_POST['nameEquipe'],$_POST['ouvert'],Member::getInstance()->getId(),$_POST['descriptionEquipe']);
    header('Location: message.php?message=votre equipe a bien été crée');
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
    	
    if($tournoi->getNbEquipeMax()>sizeof($tournoi->getEquipe()))
    		$form->appendContent(creeEquipe(Lan::createFromId($_GET['idLan'])->getLanName(),$tournoi));
    else{
    	$form->appendContent(<<<HTML
	<div class="noequipe">
		<p>Les Equipes sont pleines</p>
	</div>
HTML
    			);
    }
    $equipes=$tournoi->getEquipe();
    $form->appendContent(equipe($equipes));
    
	echo $form->toHTML();
}
else{
	header('Location: message.php?message=un problème est survenu');
}

function equipe($equipe){
	if(sizeof($equipe)==0)
		return "";
	$html = <<<HTML
	<form method="POST" name="rejoindreEquipe" action="rejoindreTournoi.php?idLan={$_GET['idLan']}&idTournoi={$_GET['idTournoi']}">
	<table class="equipe">
		<tr>
			<td>Nom</td>
			<td>Description</td>
			<td>Statut Inscriptions</td>
			<td>Rejoindre</td>
		</tr>
	
HTML;
    foreach($equipe as $e){
       $ouverte = "Fermées";
       $bouton = "";
		if($e->getInscriptionOuverte()){
			$ouverte = "Ouvertes";

			$pdo = MyPDO::getInstance();
			$stmt = $pdo->prepare(<<<SQL
			SELECT idMembre
			FROM Composer
			WHERE idEquipe=:idEquipe;
SQL
			);
			try{
				$stmt->execute(array("idEquipe" => $e->getIdEquipe()));
			}
			catch(Exception $e){
				header('Location: message.php?message=Un probleme est survenu');
			}
			$res=$stmt->fetchAll();
			if(Tournoi::getTournoiFromLAN($_GET['idLan'], $_GET['idTournoi'])->getNbPersMaxParEquipe()>sizeof($res))
				$bouton = "<button type=\"submit\" name=\"rejoindre\" value=\"{$e->getIdEquipe()}\">Rejoindre</button>";
			else
				$bouton = "plein";
		}
		$html .= <<<HTML
		<tr>
			<td>{$e->getNomEquipe()}</td>
			<td>{$e->getDescriptionEquipe()}</td>
			<td>{$ouverte}</td>
			<td>$bouton</td>
		</tr>
HTML;
	}
	$html .= <<<HTML
		</table></form>
HTML;
	return $html;
}

function creeEquipe($lan,$tournoi){
    $html= <<<HTML
    
  <h2>$lan</h2>

<form method="POST" name="creeEquipeForm" action="rejoindreTournoi.php?idLan={$_GET['idLan']}&idTournoi={$_GET['idTournoi']}">
    <table class="creeEquipeForm">
        <thead>
            <tr>
                <th colspan="2">
                    <h2>Créer une equipe : {$tournoi->getNomTournoi()}</h2>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <label for="nameEquipe">Nom de l'equipe *</label>
                    <div class="formInput">
                        <img id="nameEquipe" src="resources/img/Lan.png"/>
                        <input maxlength="31" name="nameEquipe" type="text"  placeholder="Nom">
                    </div>
                    <span id="erreurNameLAN"></span>
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
				<td>
	                 <label><input type="radio" name="ouvert" value="false" checked>Inscription ouverte</label>
	                 <label><input type="radio" name="ouvert" value="true">Inscription fermée</label
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