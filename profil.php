<?php

require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/requestUtils.inc.php');

$member = Member::getInstance();
$wrongPass = "";

// Si l'utilisateur à demander un changement de mot de passe
if(verify($_POST, 'lastPassHidden') && verify($_POST, 'newPassHidden')) {
	$lastPass = $_POST['lastPassHidden'];
	$newPass = $_POST['newPassHidden'];

	// On vérifie la validité de l'ancien mot de passe
	$validPass = selectRequest(array("id" => $member->getId(), "lastPass" => $lastPass), array(PDO::FETCH_ASSOC => null), "*", "Membre", "idMembre = :id AND password = :lastPass");

	if(isset($validPass[0])) {
		// On met à jour et on deconnecte l'utilisateur
		updateRequest(array("id" => $member->getId(), "lastPass" => $lastPass, "pass" => $newPass), "Membre", "password = :pass", "idMembre = :id AND password = :lastPass");

		Member::disconnect();
		header('Location: message.php?message=Votre mot de passe a bien été modifié');
	} else
		$wrongPass = "Mot de passe incorrecte";
}

// On récupère le nom des lans de l'utilisateur
$lans = selectRequest(array("member" => $member->getId()), array(PDO::FETCH_CLASS => "Lan"),
	"nomLAN",
	"LAN l INNER JOIN Membre m ON m.idMembre = l.idMembre",
	"m.idMembre = :member",
	"LIMIT 0, 5");

$lanList = count($lans) == 0 ? "<span>Vous n'avez pas encore créé de Lan</span>" : "<table>";

foreach($lans as $l)
	$lanList .= "<tr><td>{$l->getLanName()}</td></tr>";

if(count($lans) != 0)
	$lanList .= "<tr><td>...</td></tr></table><a href='listeLansMembre.php'>Gérer mes Lans</a>";

$webpage = new GeekOnLanWebpage("GeekOnLan - Profil");
$webpage->appendJsUrl("js/deleteAccount.js");
$webpage->appendJsUrl("js/inscription.js");
$webpage->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js");
$webpage->appendCssUrl("style/regular/profil.css", "screen and (min-width: 680px)");
$webpage->appendCssUrl("style/mobile/profil.css", "screen and (max-width: 680px)");

$html = <<<HTML
<table id="profilTable">
	<tr>
		<td colspan="2">
			<div id="persoInfo">
				<h2>Profil</h2>
				<div>
					<h3>Informations personelles</h3>
					<hr/>
					<table>
						<tr>
							<td>Pseudonyme</td>
							<td>{$member->getPseudo()}</td>
						</tr>
						<tr>
							<td>Mail</td>
							<td>{$member->getMail()}</td>
						</tr>
					</table>
				</div>
				<div>
					<h3>Informations complémentaires</h3>
					<hr/>
					<table>
						<tr>
							<td>Prenom</td>
							<td>{$member->getFirstName()}</td>
						</tr>
						<tr>
							<td>Nom</td>
							<td>{$member->getLastName()}</td>
						</tr>
						<tr>
							<td>Anniversaire</td>
							<td>{$member->getBirthday()}</td>
						</tr>
					</table>
				</div>
				<button type='button' id='buttonDelete'>Supprimer ce compte</button>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<form id="changeForm" name="change" method="POST" action="profil.php">
				<h2>Mot de passe</h2>
				<table>
					<tr>
						<td><label for="lastPass">Ancien mot de passe*</label></td>
						<td>
							<img src="resources/img/Lock.png"/>
							<input id="lastPass" name="lastPass" type="password" placeholder="Ancien mot de passe">
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2">$wrongPass</td>
					</tr>
					<tr>
						<td><label for="newPass">Nouveau mot de passe*</label></td>
						<td>
							<img src="resources/img/Lock.png"/>
							<input id="newPass" name="pwd" type="password" onfocus="resetPWD()" onblur="verifyPassForm()" placeholder="Nouveau mot de passe">
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2"><span id="erreurpass1"></span></td>
					</tr>
					<tr>
						<td><label for="newPassVerify">Confirmer nouveau mot de passe*</label></td>
						<td>
							<img src="resources/img/Lock.png"/>
							<input id="newPassVerify" name="pwdVerif" type="password" onfocus="resetPWD()" onblur="verifyPassForm()" placeholder="Confirmer mot de passe">
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2"><span id="erreurpass"></span></td>
					</tr>
					<tr></tr>
					<tr>
						<td>* : champs obligatoires</td>
						<td colspan="2"><button type="button" onclick="sha256modif()">Confirmer</button></td>
					</tr>
				</table>

				<input id="lastPassHidden" name="lastPassHidden" type="hidden">
				<input id="newPassHidden" name="newPassHidden" type="hidden">
			</form>
		</td>
		<td id="myLans">
			<h2>Mes Lans</h2>
			$lanList
		</td>
	</tr>
</table>
HTML;

// Boite de dialogue pour confirmer la suppression
$prompt = <<<HTML
		<div id="myPrompt">
			<h2>Supprimer ce compte ?</h2>
			<form id="formDelete" name="delete" method="POST" action="deleteAccount.php">
				<button type="button" id="idConfirmer" value="Confirmer" >Confirmer</button>
				<button type="button" id="idAnnuler" value="Annuler">Annuler</button>
		 	</form>
		</div>
HTML;


$webpage->appendContent($html);
$webpage->appendForeground($prompt);

echo $webpage->toHTML();