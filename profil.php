<?php

require_once('includes/connectedMember.inc.php');
require_once('includes/autoload.inc.php');
require_once('includes/utility.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Profil");
$webpage->appendJsUrl("js/deleteAccount.js");
$webpage->appendCssUrl("style/regular/deleteAccount.css","screen and (min-width: 680px");
$webpage->appendCssUrl("style/mobile/deleteAccount.css","screen and (max-width: 680px)");

//recupere le message envoyer par change.php si une erreur est survenue
$message = '';
if(verify($_GET,'message')){
	$message='<p>'.$_GET['message'].'</p>';
}
//list a puce des informations du membre
$html = '<ul>';

$member = Member::getInstance();

//information relaive au membre
$html.='<li>'.$member->getPseudo().'</li>';
$last = $member->getLastName();
if($last!='')
	$html.='<li>'.$last.'</li>';
$first = $member->getFirstName();
if($first!='')
	$html.='<li>'.$first.'</li>';
$html.='<li>'.$member->getMail().'</li>';
$birth = $member->getBirthday();
if($birth!='')
	$html.='<li>'.$birth.'</li>';
$html.='</ul>';

//formulaire de changement de mot de passe
$html.=<<<HTML
	<table>
	{$message}
		<form id="changeForm" name="change" method="POST" action="change.php">
			<tr>
				<td>
					<label for="lastPass">ancien mot de passe</label>
				</td>
				<td>
					<input id="lastPass" name="lastPass" type="password">
				</td>
			</tr>
			<tr>
				<td>
					<label for="newPass">nouveau mot de passe</label>
				</td>
				<td>
					<input id="newPass" name="newPass" type="password" onfocus="resetPWD()" onblur="verifyPassForm()">
					<span id="erreurpass"></span>
				</td>
			</tr>
			<tr>
				<td>
					<label for="newPassVerify">confimer nouveau mot de passe</label>
				</td>
				<td>
					<input id="newPassVerify" name="newPassVerify" type="password" onfocus="resetPWD()" onblur="verifyPassForm()">
					<span id="erreurpass1"></span>
				</td>
			</tr>
			<tr>
				<td>
					<button type="button" onclick="sha256()">Confirmer</button>
				</td>
			</tr>
				<input id="lastPassHidden" name="lastPassHidden" type="hidden">
				<input id="newPassHidden" name="newPassHidden" type="hidden">
		</form>
	</table>
HTML;

$webpage->appendJsUrl("js/changePassword.js");
$webpage->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js");

//boutton du suppression du compte
$html.="<button type='button' id='buttonDelete'>supprimer ce compte</button>";

//boite de dialogue pour confirmer la suppression
$prompt=<<<HTML
		<div id="myPrompt">
			<h2>Supprimer ce compte ?</h2>
			<form id="formDelete" name="delete" method="POST" action="deleteAccount.php">
				<button type="button" id="idConfirmer" value="Confirmer" >Confirmer</button>
				<button type="button" id="idAnnuler" value="Annuler">Annuler</button>
		 	</form>
		</div>
HTML;


$webpage -> appendContent($html);
$webpage -> appendForeground($prompt);
echo $webpage->toHTML();
