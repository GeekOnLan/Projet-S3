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
    //On v�rifie que le pseudonyme du futur membre n'est pas d�j� utilis�
    $stmt->execute(array("pseudo"=>$pseudo));
    $pFound = $stmt->fetch();
    if ($pFound != null) {
        $form->appendContent("<p>Le pseudonyme existe d&#233;j&#224;</p>".formulaire());
    } else {
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
            $form->appendContent("<p>Vous &#234;tes bien inscrit ! Vous allez recevoir un email de confirmation</p>");
    }
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
		<div>Pseudonyme  <input name="pseudo" type="text"  onfocus="resetInput('pseudo')" onblur="verififyPseudoForm()"></div>
		<div>Email   <input name="mail" type="text"  onfocus="resetInput('mail')" onfocus="verifyMail()" onblur="verifyMail()"><span id="erreurmail"></span></div>
		<div>Pr&#233;nom  <input name="firstName" type="text"></div>
		<div>Nom  <input name="lastName" type="text"></div>
		<div>Date de naissance  <input name="birthday" type="text"></div>	
		<div> Mot de passe   <input name="pwd" type="password" onfocus="resetInput('pwd')" onfocus="verifyPass()" onblur="verifyPass()"></div>
		<div> Retappez votre mot de passe   <input name="pwdVerif" type="password" onfocus="verifyPass()" onblur="verifyPass()"><span id="erreurpass"></span></div>
		<div><input name="hidden" type="hidden"></div>
		<button type="button" onclick="verifyInscription()"> Envoyer </button>
		</form>
HTML;
	return $html;
	
}



