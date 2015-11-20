<?php

require_once('includes/autoload.inc.php');
require_once('classes/Lan.class.php');
require_once('includes/utility.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/deconnectedMember.inc.php');

$form = new GeekOnLanWebpage("GeekOnLan - Create - LAN");
$form->appendCssUrl("style/regular/inscription.css", "screen and (min-width: 680px");
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
            Lan::addLan($nameLAN,$dateLAN,$adresseLAN,$villeLAN);
            $form->appendContent("<p>Votre LAN à bien été créer ! Vous allez recevoir un email de confirmation.</p>");
        }catch(Exception $e){
            $form->appendContent(formulaire());
        }
    }else{
        try{
            Lan::addLan($nameLAN,$dateLAN,$adresseLAN,$villeLAN,$descriptionLAN);
            $form->appendContent("<p>Votre LAN à bien été créer ! Vous allez recevoir un email de confirmation.</p>");
        }catch(Exception $e){
            $form->appendContent(formulaire());
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

<form method="POST" name="ajoutLAN" action="PasToucher_testBertrand_creerLan.php">
    <table>
        <tr>
            <td>
                Nom De La LAN
            </td><td>
                <input name="nameLAN" type="text"  onfocus="resetNameLAN()" onblur="verififyNameLAN()">
            </td><td>
                <span id="erreurNameLAN"></span>
            </td>
        </tr>
        <tr>
            <td>
                Date de lévènement
            </td><td>
                <input name="dateLAN" onfocus="resetDateLAN()" onblur="verifyDateLAN()" type="text">
            </td><td>
                <span id="erreurDateLAN"> jj/mm/yyyy</span>
            </td>
        </tr>
        <tr>
            <td>
                Déscription de la LAN <br> 80 caractères maximum
            </td><td>
                <textarea maxlength="90" name="descriptionLAN" type="text"  onfocus="resetDescriptionLAN()" onblur="verifyDescriptionLAN()"></textarea>
            </td><td>
                <span id="erreurDescriptionLAN"></span>
            </td>
        </tr>
        <tr>
            <td>
                Ville
            </td><td>
                <input name="villeLAN" type="text" onfocus="resetVilleLAN()" onblur="verifyVilleLAN()">
            </td><td>
                <span id="erreurVilleLAN"></span>
            </td>
        </tr>
        <tr>
            <td>
                Adresse
            </td><td>
                <input name="adresseLAN" type="text" onfocus="resetAdresseLAN()" onblur="verifyAdresseLAN()">
            </td><td>
                <span id="erreurAdresseLAN"></span>
            </td>
        </tr>
    </table>
    <button type="button" onclick="verifyInscription()"> Envoyer </button>
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
    $form->appendJsUrl("js/PasToucher_cree_Lan.js");
}
