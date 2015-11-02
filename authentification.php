<?php

require_once("includes/autoload.inc.php");


$form = new Webpage("GeekOnLan - Connexion");
$form->appendBasicCSSAndJS();


$err ='';

// Si le membre n'est pas connecté
if(!Member::isConnected()){

	// Authentifie le membre et le redirige sur index.php ( si les données sont valides)
	if(isset($_REQUEST['hiddenlogin']) && isset($_REQUEST['hiddenpass']) && !empty($_REQUEST['hiddenlogin']) && !empty($_REQUEST['hiddenpass'])){
		try{
			$member = Member::createFromAuth($_REQUEST['hiddenlogin'],$_REQUEST['hiddenpass']);
			$member->saveIntoSession();
			header('Location: index.php');
			exit();
		}
		catch (Exception $e) {
			$err ='<div>Un problème est survenu &nbsp; :'.$e->getMessage().'</div>';
		}
	}

	//ajoute le script de cryptage de pseudo et mot de passe
	$form->appendJsUrl("js/cryptage.js");
	//script de hashage en sha256
	$form->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js");

	//Affichage du formulaire
	$form->appendContent(<<<HTML
	{$err}
	<article>
		<form name="connexion" action="authentification.php" method="post">
			<table>
				<tr>
					<td>Identifiant :</td>
					<td><input type="text" required="required" name="login"></td>
				</tr>
				<tr>
					<td>Mot de Passe :</td>
					<td><input type="password" required="required" name="pass"></td>
				</tr>
				<tr>
					<td><input type="text" required="required" name="hiddenlogin" style="display:none"></td>
				</tr>
				<tr>
					<td><input type="password" required="required" name="hiddenpass" style="display:none"></td>
				</tr>
				<tr>
					<td colspan='2'><button type="button" value="submit" onclick="sha256()">Confirmer</button></td>
				</tr>
			</table>
		</form>
	</article>
HTML
	);
}

//Si le membre est connecté, le deconnecte
else{
	Member::disconnect();
	header('Location: index.php');
	exit();
}

echo $form->toHTML();


