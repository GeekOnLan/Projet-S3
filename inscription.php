<?php

require_once("includes/autoload.inc.php");
require_once("includes/myPDO.inc.php");
require_once("includes/verify.inc.php");

$form = new Webpage("GeekOnLan - Inscription");
$form->appendBasicCSSAndJS();

//On regarde si l'utilisateur � d�j� ex�cut� le formulaire
if (verify($_POST,"pseudo") && verify($_POST,"mail") && verify($_POST,"pwd")){
    $pseudo = $_POST['pseudo'];
    $mail = $_POST['mail'];
    $password = $_POST['pwd'];
    $passwordVerif = $_POST['pwdVerif'];

    //Test des champs non obligatoire
    if(verify($_POST,"firstName"))$fN = $_POST['firstName']; else $fN = null;
    if(verify($_POST,"lastName"))$lN = $_POST['lastName']; else $lN = null;
    if(verify($_POST,"birthday"))$lN = $_POST['birthday']; else $bD = null;
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
        // On v�rifie que les deux mots de passes sont �gaux
        if ($password == $passwordVerif) {
            $password = sha1($password);
            $stmt = $pdo->prepare(<<<SQL
			INSERT INTO `membre`(`nom`, `prenom`, `pseudo`, `mail`, `dateNais`, `password`)
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
        } else {
            $form->appendContent("<p>Les deux mots de passe ne coresspondent pas !</p>". formulaire());
        }
    }
}

else{
	$form->appendContent(formulaire());
}
echo $form->toHTML();


// Fonction utilis� pour cr�e le formulaire d'inscription au sein de la page.
function formulaire(){

	$html= <<<HTML
	<form method="POST">
		<div>Pseudonyme  <input name="pseudo" type="text" required></div>
		<div>Email   <input name="mail" type="email" required></div>
		<div>Pr&#233;nom  <input name="firstName" type="text"></div>
		<div>Nom  <input name="lastName" type="text"></div>
		<div>Date de naissance  <input name="birthday" type="text"></div>	
		<div> Mot de passe   <input name="pwd" type="password" required></div>
		<div> Retappez votre mot de passe   <input name="pwdVerif" type="password" required></div>
		<button type="submit"> Envoyer </button>
		</form>
HTML;
	return $html;
	
}



