<?php

require_once("includes/autoload.inc.php");


$page = new Webpage("Connexion");


$err ='';



// Si le formulaire a été rempli
if(isset($_REQUEST['login']) && isset($_REQUEST['pass'])){
	try{
   		$member = Member::createFromAuth($_REQUEST['login'],$_REQUEST['pass']);
   	   	$member->saveIntoSession();
		header('Location: index.php');
		exit();
	}
	catch (Exception $e) {
	    $err ='<div>Un problème est survenu &nbsp;: {$e->getMessage()}</div>';
	}
}

//Affichage du formulaire
$page->appendContent(<<<HTML
	    
		<article>
			<form action="connection.php" method="post">
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

//Ajout de la deconnection si deja connecté

echo $page->toHTML();


