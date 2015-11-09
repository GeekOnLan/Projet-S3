<?php
//w
// Préparation du mail contenant le lien d'activation
function envoieMailValide($login,$email){
	//génération aléatoire d'une clé
	$key = md5(microtime(TRUE)*100000);

	// Insertion de la clé dans la base de données
	$stmt = myPDO::GetInstance();
	$stmt = $dbh->prepare("UPDATE Membre SET cleMail=:key WHERE pseudo like :login");
	$stmt->bindParam(':key', $key);
	$stmt->bindParam(':login', $login);
	$stmt->execute();

 	$destinataire = $email;
	$sujet = "Activation de votre compte sur geekonlan" ;
	$entete = "From: inscription@geekonlan.com" ;
	$key = urlencode($key);
	$login = urlencode($login);
	//chemin du site
	$path = $_SERVER['DOCUMENT_ROOT'];
	// Le lien d'activation est composé du login(login) et de la clé(key)
	$message =<<<HTML
Bienvenue sur GeekOnLAN,

Pour activer votre compte, veuillez cliquer sur le lien ci dessous
ou le copier/coller dans votre navigateur internet.
{$path}/Projet-S3/activation.php?log={$login}&key={$key}


---------------
Ceci est un mail automatique, Merci de ne pas y répondre

HTML;

	mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail
}
