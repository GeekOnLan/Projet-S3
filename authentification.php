<?php

require_once("includes/autoload.inc.php");


$form = new Webpage("Connexion");
$form->appendCssUrl("style/header.css");
$form->appendCssUrl("style/accueil.css");


$err ='';

// Si le membre n'est pas connecté
if(!Member::isConnected()){

	// Authentifie le membre et le redirige sur index.php ( si les données sont valides)
	if(isset($_REQUEST['login']) && isset($_REQUEST['pass'])){
		try{
	   		$member = Member::createFromAuth($_REQUEST['login'],$_REQUEST['pass']);
	   	   	$member->saveIntoSession();
			header('Location: index.php');
			exit();
		}
		catch (Exception $e) {
		    $err ='<div>Un problème est survenu &nbsp;:'.$e->getMessage().'</div>';
		}
	}

	//Affichage du formulaire 
	$form->appendContent(<<<HTML
		    {$err}
			<article>
				<form action="authentification.php" method="post">
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
							<td colspan='2'><button type="submit" value="submit">Confirmer</button></td>
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


