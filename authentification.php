<?php

require_once("includes/autoload.inc.php");
require_once("includes/utility.inc.php");

$form = new Webpage("GeekOnLan - Connexion");
$form->appendBasicCSSAndJS();


$err ='';

// Si le membre n'est pas connecté
if(!Member::isConnected()){

	// Authentifie le membre et le redirige sur index.php ( si les données sont valides)
	if(verify($_POST,'hidden')){
		try{
			$member = Member::createFromAuth($_POST['hidden']);
			$member->saveIntoSession();
			header('Location: index.php'.SID);
			exit();
		}
		catch (Exception $e) {
			$err ='<div>Un problème est survenu &nbsp; : '.$e->getMessage().'</div>';
		}
	}

	//ajoute le script de cryptage de pseudo et mot de passe
	$form->appendJsUrl("js/cryptageAuthentification.js");
	//script de hashage en sha256
	$form->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js");
	//script de hashage en sha1
	$form->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js");

	//grain de sel
	$salt = Member::SaltGrain();

	//Affichage du formulaire
	$form->appendContent(<<<HTML
	{$err}
	<article>
		<form name="connexion" action="authentification.php" method="post">
			<table>
				<tr>
					<td>Identifiant :</td>
					<td><input type="text" name="login" onfocus="resetInput('login')"></td>
				</tr>
				<tr>
					<td>Mot de Passe :</td>
					<td><input type="password" name="pass" onfocus="resetInput('pass')"></td>
				</tr>
				<tr>
					<td><input type="text" name="hidden" style="display:none" value="{$salt}"></td>
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
