<?php

require_once('includes/autoload.inc.php');
require_once('classes/Lan.class.php');
require_once('includes/utility.inc.php');
require_once('includes/connectedMember.inc.php');

$form = new GeekOnLanWebpage("GeekOnLan - Création d'une LAN");
$form->appendCssUrl("style/regular/creeTournoi.css", "screen and (min-width: 680px");
$form->appendCssUrl("style/mobile/creeTournoi.css", "screen and (max-width: 680px");
$form->appendJsUrl("js/rsa.js");
$form->appendJsUrl("js/BigInt.js");
$form->appendJsUrl("js/creeTournoi.js");

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
	
    try {
        $LAN = Member::getInstance()-> getLAN() [$_GET['idLan']];
        //var_dump($_GET['idLan']);
        
        $pdo = MyPDO::getInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT idJeu
			FROM Jeu
			WHERE nomJeu = :nom ;
SQL
);
        $stmt -> execute(array(':nom' => $nameJeu));
        $idJeu = $stmt -> fetch()['idJeu']; 
        
        $LAN -> addTournoi($idJeu,$nameTournoi,1,$nbEquipeMax,$nbMembreMax,$dateTournoi,$descriptionTournoi);
        header('Location: message.php?message=Votre LAN a bien été créée ! Vous allez recevoir un email de confirmation');
    } catch(Exception $e) {
    var_dump($e);
    var_dump($LAN);
       // header('Location: message.php?message=un problème est survenu');
    }
    //envoieMailValide($pseudo, $mail);
} else {
	$LAN = Member::getInstance()-> getLAN() [$_GET['idLan']];
	$LAN = $LAN->getLanDate();
	
    $form->appendContent(<<<HTML
    <div name="dateLAN" style="display:none">{$LAN}</div>
<form method="POST" name="ajoutTournoi" action="creeTournoi.php?idLan={$_GET['idLan']}">
    <table class="tournoiForm">
        <thead>
            <tr>
                <th colspan="2">
                    <h2>Créer un tournoi</h2>
                </th>
		    </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <label for="nameTournoi">Nom du tournoi *</label>
                    <div class="formInput">    		
                        <img id="tournoiName" src="resources/img/Lan.png"/>
                        <input maxlength="31" name="nameTournoi" type="text"  placeholder="Nom Tournoi" onfocus="resetNameTournoi()" onblur="verifyNameTournoi()">
                    </div>
                    <span id="erreurNameTournoi"></span>
                </td>
                <td>
                    <label for="nameJeuTournoi">Nom du jeu *</label>
                    <div class="formInput">
                        <img id="tournoiJeuName" src="resources/img/Lan.png"/>
                        <input maxlength="31" name="nameJeuTournoi" type="text"  placeholder="Nom jeu" onfocus="resetNameJeuTournoi()" onblur="verifyNameJeuTournoi()">
                    </div>
                    <span id="erreurNameJeuTournoi"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="dateTournoi">Date du tournoi *</label>
                    <div class="formInput">
                        <img src="resources/img/Birthday.png"/>
                        <input maxlength="12" name="dateTournoi" placeholder="JJ/MM/AAAA" onfocus="resetDateTournoi()" onblur="verifyDateTournoi()" type="text">
                    </div>
                    <span id="erreurDateTournoi"></span>
                </td>
                 <td>
                    <label for="heureTournoi">Heure du tournoi *</label>
                    <div class="formInput">
                        <img id="tournoi" src="resources/img/Birthday.png"/>
                        <input maxlength="5"  name="heureTournoi" type="text" placeholder="HH:MM" onfocus="resetHeureTournoi()" onblur="verifyHeureTournoi()">
                    </div>
                    <span id="erreurHeureTournoi"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="nbEquipeMax">Nombre maximum d'équipe *</label>
                    <div class="formInput">
                        <img id="nbEquipeMax" src="resources/img/Contact.png"/>
                        <input type="number" value="0" min="0" max="9999" name="nbEquipeMax" onfocus="resetNbEquipeMax()" onblur="verifyNbEquipeMax()">
                    </div>
                    <span id="erreurNbEquipeMax"></span>
                </td>
                 <td>
                    <label for="nbMembreMax">Nombre maximum de joueurs par équipe *</label>
                    <div class="formInput">
                        <img id="nbMembreMax" src="resources/img/Contact.png"/>
                        <input type="number" value="0" min="0" max="9999" name="nbMembreMax" onfocus="resetNbMembreMax()" onblur="verifyNbMembreMax()">
                    </div>
                    <span id="erreurNbMembreMax"></span>
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
                <td><button type="button" onclick="verifyTournoi()">Créer une tournoi</button></td>
                <td><button type="button" id="prev">Précédent</button></td>
            </tr>
            <tr>
                <td colspan="2"><p>* : champs obligatoires</p></td>
            </tr>
        </tbody>
    </table>
</form>
HTML
    );
}

echo $form->toHTML();
