<?php

require_once('includes/autoload.inc.php');
require_once('classes/Lan.class.php');
require_once('includes/utility.inc.php');
require_once('includes/connectedMember.inc.php');

$form = new GeekOnLanWebpage("GeekOnLan - Création d'une LAN");
$form->appendCssUrl("style/regular/creeLan.css", "screen and (min-width: 680px");
$form->appendCssUrl("style/mobile/creeLan.css", "screen and (max-width: 680px");
$form->appendJsUrl("js/rsa.js");
$form->appendJsUrl("js/BigInt.js");
$form->appendJsUrl("js/creeLan.js");
$form->appendJsUrl("js/creeTournoi.js");

/**
 * Vérifie que tout les champs obligatoires du formulaire son
 * remplis
 *
 * @return bool true s'ils sont corrects, false sinon
 */
function verifyFormLAN(){
    $res = true;
    $toVerify = array("nameLAN", "dateLAN", "villeLAN", "adresseLAN");

    foreach($toVerify as $key)
        $res = $res && verify($_POST, $key);

    return $res && isset($_POST["descriptionLAN"]);
}

// TODO Modifier cette fonction pour envoyer un vrai mail confirmant la création
/**
 * Envois un mail confirmant la création de la Lan au membre
 * @param $login
 * @param $email
 */
function envoieMailValide($login,$email){
    //génération aléatoire d'une clé
    $key = md5(microtime(TRUE)*100000);

    // Insertion de la clé dans la base de données
    $dbh = myPDO::GetInstance();
    $stmt = $dbh->prepare("UPDATE Membre SET cleMail=:key WHERE pseudo like :login");
    $stmt->bindParam(':key', $key);
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    $destinataire = $email;
    $sujet = "Activation de votre LAN sur geekonlan" ;
    $entete = "From: inscription@geekonlan.com" ;
    $key = urlencode($key);
    $login = urlencode($login);
    // Le lien d'activation est composé du login(login) et de la clé(key)
    $message =<<<HTML
Bienvenue sur GeekOnLAN,

Pour activer votre LAN, veuillez cliquer sur le lien ci dessous
ou le copier/coller dans votre navigateur internet.

http://geekonlan.com/activation.php?log={$login}&key={$key}


---------------
Ceci est un mail automatique, Merci de ne pas y répondre

HTML;

    mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail
}

//On regarde si l'utilisateur à déjà exécuté le formulaire
if (verifyFormLAN()) {
    $nameLAN = $_POST['nameLAN'];
    $dateLAN = $_POST['dateLAN'];
    $descriptionLAN = (empty($_POST["descriptionLAN"])) ? "" : $_POST["descriptionLAN"];
    $villeLAN = $_POST['villeLAN'];
    $adresseLAN = $_POST['adresseLAN'];

	$nameTournoi = $_POST['nameTournoi'];
	$nameJeu = $_POST['nameJeuTournoi'];
	$dateTournoi = $_POST['dateTournoi'];
	$heureTournoi = $_POST['heureTournoi'];
	$nbEquipeMax = $_POST['nbEquipeMax'];
	$nbMembreMax = $_POST['nbMembreMax'];
	$descriptionTournoi = $_POST['descriptionTournoi'];

    try {
        Member::getInstance()->addLan($nameLAN,$dateLAN,$adresseLAN,$villeLAN,$descriptionLAN);
        Lan::createLanFromName($nameLAN)->addTournoi($idJeu,$nameTournoi,1,$nbEquipeMax,$nbMembreMax,$dateTournoi,$description);
        header('Location: message.php?message=Votre LAN à bien été créer ! Vous allez recevoir un email de confirmation');
    } catch(Exception $e) {
        header('Location: message.php?message=un problème est survenu');
    }
    //envoieMailValide($pseudo, $mail);
} else {
    $form->appendContent(<<<HTML
<form method="POST" name="ajoutLAN" action="creeLan.php">
    <table class="lanForm">
        <thead>
            <tr>
                <th colspan="2">
                    <h2>Créer une Lan</h2>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <label for="nameLan">Nom de la LAN *</label>
                    <div class="formInput">
                        <img id="lanName" src="resources/img/Lan.png"/>
                        <input maxlength="31" name="nameLAN" type="text"  placeholder="Nom" onfocus="resetNameLAN()" onblur="verifyNameLAN()">
                    </div>
                    <span id="erreurNameLAN"></span>
                </td>
                <td rowspan="4" id="area">
                    <label for="descriptionLAN">Description de la LAN</label>
                    <div class="formTextarea">
                        <textarea maxlength="255" name="descriptionLAN" onfocus="resetDescriptionLAN" onblur="verifyDescriptionLAN()"></textarea>
                    </div>
                    <span id="erreurDescriptionLAN"> </span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="dateLAN">Date de l'évènement *</label>
                    <div class="formInput">
                        <img src="resources/img/Birthday.png"/>
                        <input maxlength="12" name="dateLAN" placeholder="Date" onfocus="resetDateLAN()" onblur="verifyDateLAN()" type="text">
                    </div>
                    <span id="erreurDateLAN"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="adresseLAN">Adresse *</label>
                    <div class="formInput">
                        <img id="adresse" src="resources/img/Ville.png"/>
                        <input maxlength="63" name="adresseLAN" type="text" placeholder="Adresse" onfocus="resetAdresseLAN()" onblur="verifyAdresseLAN()">
                    </div>
                    <span id="erreurAdresseLAN"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="villeLAN">Ville *</label>
                    <div class="formInput">
                        <img id="ville" src="resources/img/Ville.png"/>
                        <input maxlength="63" name="villeLAN" type="text" placeholder="Ville" onfocus="resetVilleLAN()" onblur="verifyVilleLAN()">
                    </div>
                    <span id="erreurVilleLAN"></span>
                </td>
            </tr>
            <tr>
                <td><button type="button" id="next">Continuer</button></td>
                <td><p>* : Champs obligatoires</p></td>
            </tr>
        </tbody>
    </table>
    <table class="tournoiForm">
        <thead>
            <tr>
                <th colspan="2">
                    <h2>Créer un premier tournoi</h2>
                </th>
		    </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <label for="nameTournoi">Nom du tournoi *</label>
                    <div class="formInput">
                        <img id="tournoiName" src=""/>
                        <input maxlength="31" name="nameTournoi" type="text"  placeholder="Nom Tournoi" onfocus="resetNameTournoi()" onblur="verifyNameTournoi()">
                    </div>
                    <span id="erreurNameTournoi"></span>
                </td>
                <td>
                    <label for="nameJeuTournoi">Nom du jeu *</label>
                    <div class="formInput">
                        <img id="tournoiJeuName" src=""/>
                        <input maxlength="31" name="nameJeuTournoi" type="text"  placeholder="Nom jeu" onfocus="resetNameJeuTournoi()" onblur="verifyNameJeuTournoi()">
                    </div>
                    <span id="erreurNameJeuTournoi"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="dateTournoi">Date du tournoi *</label>
                    <div class="formInput">
                        <img src=""/>
                        <input maxlength="12" name="dateTournoi" placeholder="JJ/MM/AAAA" onfocus="resetDateTournoi()" onblur="verifyDateTournoi()" type="text">
                    </div>
                    <span id="erreurDateTournoi"></span>
                </td>
                 <td>
                    <label for="heureTournoi">Heure du tournoi *</label>
                    <div class="formInput">
                        <img id="tournoi" src=""/>
                        <input maxlength="5"  name="heureTournoi" type="text" placeholder="HH:MM" onfocus="resetHeureTournoi()" onblur="verifyHeureTournoi()">
                    </div>
                    <span id="erreurHeureTournoi"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="nbEquipeMax">Nombre maximum d'équipe *</label>
                    <div class="formInput">
                        <img id="nbEquipeMax" src=""/>
                        <input type="number" value="0" min="0" max="9999" name="nbEquipeMax" onfocus="resetNbEquipeMax()" onblur="verifyNbEquipeMax()">
                    </div>
                    <span id="erreurNbEquipeMax"></span>
                </td>
                 <td>
                    <label for="nbMembreMax">Nombre maximum de joueurs par équipe *</label>
                    <div class="formInput">
                        <img id="nbMembreMax" src=""/>
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
                <td><button type="button" onclick="verifyLAN()">Créer une Lan</button></td>
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
