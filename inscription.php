<?php

require_once("includes/autoload.inc.php");
require_once("includes/myPDO.inc.php");
require_once("includes/utility.inc.php");

$form = new Webpage("GeekOnLan - Inscription");
$form->appendBasicCSSAndJS();

//On regarde si l'utilisateur � d�j� ex�cut� le formulaire
if (verify($_POST,"pseudo") && verify($_POST,"mail") && verify($_POST,"hidden")){
    $pseudo = $_POST['pseudo'];
    $mail = $_POST['mail'];
    $password = $_POST['hidden'];
    $fN = null;
    $lN = null;
    $bD = null;
    //Test des champs non obligatoire
    if(verify($_POST,"firstName"))$fN = $_POST['firstName']; 
    if(verify($_POST,"lastName"))$lN = $_POST['lastName']; 
    if(verify($_POST,"birthday"))$bD = $_POST['birthday'];
    //Connexion � la BdD
    $pdo = myPDO::GetInstance();
    $stmt = $pdo->prepare(<<<SQL
			SELECT pseudo
			FROM Membre
			WHERE pseudo = :pseudo;
SQL
    );
    $stmt = $pdo->prepare(<<<SQL
	INSERT INTO `Membre`(`nom`, `prenom`, `pseudo`, `mail`, `dateNais`, `password`)
	VALUES (:ln,:fn,:pseudo,:mail,:birthday,:password)
SQL
    );
    $stmt->execute(array("ln"=>$lN,
        "fn"=>$fN,
        "pseudo"=>$pseudo,
        "password"=>$password,
        "mail"=>$mail,
        "birthday"=>$bD));
    envoieMailValide($pseudo,$mail);
    $form->appendContent("<p>Vous &#234;tes bien inscrit ! Vous allez recevoir un email de confirmation</p>");
}

else{
	$form->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js");
	$form->appendJsUrl("js/inscription.js");
	$form->appendContent(formulaire());
}
echo $form->toHTML();


// Fonction utilis� pour cr�e le formulaire d'inscription au sein de la page.
function formulaire(){

	$html= <<<HTML
	<form method="POST" name="inscription" action="inscription.php">
		<div>Pseudonyme  <input name="pseudo" type="text"  onfocus="resetPseudo()" onblur="verififyPseudoForm()"><span id="erreurpseudo"></div>
		<div>Email   <input name="mail" type="text"  onfocus="resetMail()" onblur="verifyMail()"><span id="erreurmail"></span></div>
		<div>Pr&#233;nom  <input name="firstName" type="text" onfocus="resetFirst()" onblur="verifyFirst()"><span id="erreurfirst"></div>
		<div>Nom  <input name="lastName" type="text" onfocus="resetLast()" onblur="verifyLast()"><span id="erreurlast"></div>
		<div>Date de naissance  <input name="birthday" onfocus="resetBirth()" onblur="verifyBirth()" type="text"><span id="erreurbirth"></span></div>
		<div> Mot de passe   <input name="pwd" type="password" onfocus="resetPWD()" onblur="verifyPass()"><span id="erreurpass1"></span></div>
		<div> Retappez votre mot de passe   <input name="pwdVerif" type="password" onfocus="resetPWD()" onblur="verifyPass()"><span id="erreurpass"></span></div>
		<div><input name="hidden" type="hidden"></div>
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
	$sujet = "Activation de votre compte sur geekonlan" ;
	$entete = "From: inscription@geekonlan.com" ;
	$key = urlencode($key);
	$login = urlencode($login);
	// Le lien d'activation est composé du login(login) et de la clé(key)
	$message =<<<HTML
Bienvenue sur GeekOnLAN,

Pour activer votre compte, veuillez cliquer sur le lien ci dessous
ou le copier/coller dans votre navigateur internet.

http://geekonlancom/activation.php?log={$login}&key={$key}


---------------
Ceci est un mail automatique, Merci de ne pas y répondre

HTML;

	mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail
}


