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
			header('Location: index.php');
			exit();
		} catch (Exception $e) {
			header('Location: message.php?message=Login ou mot de passe incorecte');
		}
	}
}
//Si le membre est connecté, le deconnecte
else{
	Member::disconnect();
	header('Location: index.php');
	exit();
}