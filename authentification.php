<?php

require_once('includes/autoload.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/deconnectedMember.inc.php');

// Si le membre n'est pas connecté
if(!Member::isConnected()) {

	// Authentifie le membre et le redirige sur index.php ( si les données sont valides)
	if (verify($_POST, 'hiddenCrypt')) {
		try {
			$member = Member::createFromAuth($_POST['hiddenCrypt']);
			$member->saveIntoSession();
			header('Location: index.php' . SID);
			exit();
		} catch (Exception $e) {
			$webpage = new GeekOnLanWebpage("GeekOnLan - Connexion");
			$webpage -> appendContent('<div>Un problème est survenu &nbsp; : ' . $e->getMessage() . '</div>');
			echo $webpage->toHTML();
		}
	}
}
//Si le membre est connecté, le deconnecte
else{
	Member::disconnect();
	header('Location: index.php');
	exit();
}