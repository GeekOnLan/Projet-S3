<?php
//w
require_once($_SERVER['DOCUMENT_ROOT'].'/Projet-S3/includes/autoload.inc.php');

//création de la page web
$page=new GeekOnLanWebpage("GeekOnLan - Validation Mail");

// Connexion à la base de données
require_once("includes/myPDO.inc.php");
$pdo = myPDO::GetInstance();

// Récupération des variables nécessaires
$pseudo = $_GET['log'];
$key = $_GET['key'];

// Récupération de la clé correspondant au $login dans la base de données
$stmt = $pdo->prepare("SELECT cleMail,estValide FROM Membre WHERE pseudo like :pseudo ");
if($stmt->execute(array(':pseudo' => $pseudo)) && $row = $stmt->fetch()){
	$cleMail = $row['cleMail'];	// Récupération de la clé
	$estValide = $row['estValide']; // $actif contiendra alors 0 ou 1
}

// On teste la valeur de la variable $actif récupéré dans la BDD
if($estValide == '1')$page->appendContent("Votre compte est déjà actif !");

// Si ce n'est pas le cas on passe aux comparaisons
else {
	// On compare nos deux clés
	if($key == $cleMail){
		// Si elles correspondent on active le compte !
		$page->appendContent("Votre compte a bien été activé !");

		// La requéte qui va passer notre champ actif de 0 à 1
		$stmt = $pdo->prepare("UPDATE Membre SET estValide = 1 WHERE pseudo like :pseudo ");
		$stmt->bindParam(':pseudo', $pseudo);
		$stmt->execute();
	}

	// Si les deux clés sont différentes on provoque une erreur...
	else $page->appendContent("Erreur ! Votre compte ne peut ètre activé...");
}
 
echo($page->toHTML());
