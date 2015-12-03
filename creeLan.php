<?php

require_once('includes/autoload.inc.php');
require_once('classes/Lan.class.php');
require_once('includes/utility.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/connectedMember.inc.php');

$form = new GeekOnLanWebpage("GeekOnLan - Create - LAN");
$form->appendCssUrl("style/regular/creeLan.css", "screen and (min-width: 680px");
$form->appendCssUrl("style/mobile/creeLan.css", "screen and (max-width: 680px");


function verifyFormLAN(){
    $res=true;
    $res=$res&&(isset($_POST['nameLAN'])&&!empty($_POST['nameLAN']));
    $res=$res&&(isset($_POST['dateLAN'])&&!empty($_POST['dateLAN']));
    $res=$res&&(isset($_POST['descriptionLAN']));
    $res=$res&&(isset($_POST['villeLAN'])&&!empty($_POST['villeLAN']));
    $res=$res&&(isset($_POST['adresseLAN'])&&!empty($_POST['adresseLAN']));
    return $res;
}
//On regarde si l'utilisateur � d�j� ex�cut� le formulaire
if (verifyFormLAN()) {
    $nameLAN = $_POST['nameLAN'];
    $dateLAN = $_POST['dateLAN'];
    $descriptionLAN = $_POST['descriptionLAN'];
    $villeLAN = $_POST['villeLAN'];
    $adresseLAN = $_POST['adresseLAN'];
    if(empty($descriptionLAN)){
        try{
            Member::getInstance()->addLan($nameLAN,$dateLAN,$adresseLAN,$villeLAN);
            header('Location: message.php?message=Votre LAN à bien été créer ! Vous allez recevoir un email de confirmation');
        }catch(Exception $e){
            header('Location: message.php?message=un problème est survenu');
        }
    }else{
        try{
            Member::getInstance()->addLan($nameLAN,$dateLAN,$adresseLAN,$villeLAN,$descriptionLAN);
            header('Location: message.php?message=Votre LAN à bien été créer ! Vous allez recevoir un email de confirmation');
        }catch(Exception $e){
            header('Location: message.php?message=un problème est survenu');
        }
    }
    //envoieMailValide($pseudo, $mail);
}else{
    $form->appendContent(formulaire());
}
addJsAndCss($form);
echo $form->toHTML();

// Fonction utilis� pour cr�e le formulaire d'inscription au sein de la page.
function formulaire(){
$html= <<<HTML

<form method="POST" name="ajoutLAN" action="creeLan.php">
    <h2>Crée une Lan</h2>
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
// Préparation du mail contenant le lien d'activation
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

function addJsAndCss(GeekOnLanWebpage $form){
    $form->appendJsUrl("js/rsa.js");
    $form->appendJsUrl("js/BigInt.js");
    $form->appendJsUrl("js/creeLan.js");
}
