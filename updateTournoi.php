<?php

require_once('includes/autoload.inc.php');
require_once('classes/Lan.class.php');
require_once('includes/utility.inc.php');
require_once('includes/connectedMember.inc.php');

$form = new GeekOnLanWebpage("GeekOnLan - Mise à jour du tournoi");
$form->appendCssUrl("style/regular/updateTournoi.css", "screen and (min-width: 680px)");
$form->appendJsUrl("js/rsa.js");
$form->appendJsUrl("js/BigInt.js");
$form->appendJsUrl("js/updateTournoi.js");
$form->appendJsUrl("js/creeTournoi.js");
$form->appendJsUrl("js/deleteTournoi.js");

/**
 * Vérifie que tout les champs obligatoires du formulaire son
 * remplis
 *
 * @return bool true s'ils sont corrects, false sinon
 */
function verifyFormTournoi(){
    $res = true;
    $toVerify = array("nameTournoi", "nameJeuTournoi", "dateTournoi", "heureTournoi","nbEquipeMax","nbMembreMax");
	
    foreach($toVerify as $key)
        $res = $res && verify($_POST, $key);
    return $res && isset($_POST["descriptionTournoi"]);
}
//On regarde si l'utilisateur à déjà exécuté le formulaire
if (verifyFormTournoi()) {
	 $LAN = Member::getInstance()->getLAN();
	
	$nameTournoi = $_POST['nameTournoi'];
	$nameJeu = $_POST['nameJeuTournoi'];
	$dateTournoi = $_POST['dateTournoi'];
	$heureTournoi = $_POST['heureTournoi'];
	
	$dateTournoi=(substr($dateTournoi,0,2))."/".(substr($dateTournoi,3,2))."/".(substr($dateTournoi,6));
	$heureTournoi=(substr($heureTournoi,0,2)).":".(substr($heureTournoi,3));
	
	$dateTournoi = $dateTournoi." ".$heureTournoi;
	
	
	$nbEquipeMax = $_POST['nbEquipeMax'];
	$nbMembreMax = $_POST['nbMembreMax'];
	$descriptionTournoi = $_POST['descriptionTournoi'];
	
	try{
		$tournoi = Tournoi::createFromId($LAN[$_GET['idLan']]->getId(),$_GET['idTournoi']);
	}catch(Exception $e){
		header('Location: message.php?message=get tournoi erreur');
	}
	
    try {
        
        $pdo = MyPDO::getInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT idJeu
			FROM Jeu
			WHERE nomJeu = :nom ;
SQL
);
        $stmt -> execute(array(':nom' => $nameJeu));
        $idJeu = $stmt -> fetch()['idJeu']; 
    } catch(Exception $e) {
       header('Location: message.php?message=Un problème est survenu');
    }
	try{
		$tournoi-> update($idJeu,$nameTournoi,1,$nbEquipeMax,$nbMembreMax,$dateTournoi,$descriptionTournoi);
		header('Location: message.php?message=Votre Tournoi a bien été ajouter !');
	}catch(Exception $e){
		header('Location: message.php?message=Un problème est survenu lors de la mise à jour');
	}
} elseif(isset($_GET['idLan']) && is_numeric($_GET['idTournoi']) && isset($_GET['idTournoi']) && is_numeric($_GET['idTournoi'])) {
    $LAN = null;
    try {
        $LAN = Member::getInstance()->getLAN();
    }
    catch(Exception $e){
        header('Location: message.php?message=Un problème est survenu recup lan');
    }

    if($_GET['idLan']>sizeof($LAN) || $_GET['idLan']<0)
        header('Location: message.php?message=Un problème est survenu');

    $LAN=$LAN[$_GET['idLan']];
	
	$tournoi = Tournoi::createFromId($LAN->getId(),$_GET['idTournoi']);
	$heureTournoi = substr($tournoi->getDateHeurePrevu(),13,5);
	$jeu=$tournoi->getJeu()['nomJeu'];
	$date = $LAN->getLanDate();
	
	$prompt = <<<HTML
		<div id="myPrompt">
			<h2>Supprimer ce Tournoi ? ?</h2>
			<form id="formDelete" name="delete" method="POST" action="deleteTournoi.php?idLan={$_REQUEST['idLan']}&idTournoi={$_REQUEST['idTournoi']}">
				<button type="button" id="idConfirmer" value="Confirmer" >Confirmer</button>
				<button type="button" id="idAnnuler" value="Annuler">Annuler</button>
		 	</form>
		</div>
HTML;
	
    $form->appendContent(<<<HTML
    <div name="dateLAN" style="display:none">{$date}</div>
<form method="POST" name="modifTournoi" action="updateTournoi.php?idLan={$_GET['idLan']}&idTournoi={$_GET['idTournoi']}">
    <table class="tournoiForm">
        <thead>
            <tr>
                <th colspan="2">
                    <h2>mise à jour tournoi : {$LAN->getLanName()}</h2>
                </th>
		    </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <label for="nameTournoi">Nom du tournoi *</label>
                    <div class="formInput">    		
                        <img id="tournoiName" src="resources/img/Lan.png"/>
                        <input value="{$tournoi->getNomTournoi()}" maxlength="31" name="nameTournoi" type="text"  placeholder="Nom Tournoi" onfocus="resetNameTournoi()" onblur="verifyNameTournoi()">
                    </div>
                    <span id="erreurNameTournoi"> </span>
                </td>
                <td>
                    <label for="nameJeuTournoi">Nom du jeu *</label>
                    <div class="formInput">
                        <img id="tournoiJeuName" src="resources/img/Lan.png"/>
                        <input value="{$jeu}" maxlength="31" name="nameJeuTournoi" type="text"  placeholder="Nom jeu" onfocus="resetNameJeuTournoi()" onblur="verifyNameJeuTournoi()">
                    </div>
                    <span id="erreurNameJeuTournoi"> </span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="dateTournoi">Date du tournoi *</label>
                    <div class="formInput">
                        <img src="resources/img/Birthday.png"/>
                        <input maxlength="12" name="dateTournoi" value="{$date}" placeholder="JJ/MM/AAAA" onfocus="resetDateTournoi()" onblur="verifyDateTournoi()" type="text">
                    </div>
                    <span id="erreurDateTournoi"> </span>
                </td>
                 <td>
                    <label for="heureTournoi">Heure du tournoi *</label>
                    <div class="formInput">
                        <img id="tournoi" src="resources/img/Birthday.png"/>
                        <input maxlength="5" value="{$heureTournoi}" name="heureTournoi" type="text" placeholder="HH:MM" onfocus="resetHeureTournoi()" onblur="verifyHeureTournoi()">
                    </div>
                    <span id="erreurHeureTournoi"> </span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="nbEquipeMax">Nombre maximum d'équipe *</label>
                    <div class="formInput">
                        <img id="nbEquipeMax" src="resources/img/Contact.png"/>
                        <input value="{$tournoi->getNbEquipeMax()}" type="number" value="0" min="0" max="9999" name="nbEquipeMax" onfocus="resetNbEquipeMax()" onblur="verifyNbEquipeMax()">
                    </div>
                    <span id="erreurNbEquipeMax"> </span>
                </td>
                 <td>
                    <label for="nbMembreMax">Nombre maximum de joueurs par équipe *</label>
                    <div class="formInput">
                        <img id="nbMembreMax" src="resources/img/Contact.png"/>
                        <input value="{$tournoi->getNbPersMaxParEquipe()}" type="number" value="0" min="0" max="9999" name="nbMembreMax" onfocus="resetNbMembreMax()" onblur="verifyNbMembreMax()">
                    </div>
                    <span id="erreurNbMembreMax"> </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label for="descriptionTournoi">Description du tournoi</label>
                    <div class="tournoiDesc">
                        <textarea maxlength="255" name="descriptionTournoi" type="text" onfocus="resetDescriptionLAN" onblur="verifyDescriptionTournoi()"></textarea>
                    </div>
                    <span id="erreurDescriptionTournoi"> </span>
                </td>
            </tr>
            <tr>
                <td><button type="button" onclick="verifyUpdate()">Mettre à jour le Tournoi</button></td>
            	<td><button type="button" id="buttonDelete">Supprimer le Tournoi</button></td>
            </tr>
            <tr>
                <td colspan="2"><p>* : champs obligatoires</p></td>
            </tr>
        </tbody>
    </table>
</form>
HTML
    );
	$form->appendForeground($prompt);
}
else
   header('Location: message.php?message=Un problème est survenu');

echo $form->toHTML();
