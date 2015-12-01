<?php

require_once('includes/connectedMember.inc.php');
require_once('includes/autoload.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/myPDO.inc.php');

if(verify($_POST,'lastPassHidden') && verify($_POST,'newPassHidden')) {
	$pdo = myPDO::getInstance();
	$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Membre
			WHERE idMembre=:id
			  AND password=:lastPass;
SQL
	);
	$stmt->execute(array("id"=>Member::getInstance()->getId(), "lastPass" => $_POST['lastPassHidden']));
	if($stmt->fetch()!==false) {
		$pdo = myPDO::getInstance();
		$stmt = $pdo->prepare(<<<SQL
			UPDATE Membre
			SET password=:pass
			WHERE idMembre=:id
			  AND password=:lastPass;
SQL
		);
		$stmt->execute(array("id"=>Member::getInstance()->getId(), "lastPass" => $_POST['lastPassHidden'], "pass" => $_POST['newPassHidden']));
		Member::disconnect();
		header('Location: index.php');
	}
	else
		header('Location: erreur.php?erreur=mot de passe incorecte');
}
else {

	$webpage = new GeekOnLanWebpage("GeekOnLan - Profil");
	$webpage->appendJsUrl("js/deleteAccount.js");
	$webpage->appendCssUrl("style/regular/deleteAccount.css", "screen and (min-width: 680px");
	$webpage->appendCssUrl("style/mobile/deleteAccount.css", "screen and (max-width: 680px)");
	$webpage->appendCssUrl("style/regular/profil.css", "screen and (min-width: 680px");
	$webpage->appendCssUrl("style/mobile/profil.css", "screen and (max-width: 680px");


//list a puce des informations du membre
	$html = '<ul>';

	$member = Member::getInstance();

//information du membre
	$html .= <<<HTML
<form>
    <h2>Profil</h2>
    <table>
        <tr>
    			<td colspan="2">
    				<h3>Informations personelles</h3>
    				<hr/>
    			</td>
		</tr>
		<tr>
			<td colspan="2">
    			<label>Pseudonyme</label>
    			<div>
    			    <img src="resources/img/Contact.png" alt="login" />
    			    <p>{$member->getPseudo()}</p>
    			</div>
    		</td>
    	</tr>
    	<tr>
			<td colspan="2">
    			<label>Mail</label>
    			<div>
    			    <img src="resources/img/Mail.png" alt="login" />
    			    <p>{$member->getMail()}</p>
    			</div>
    		</td>
    	</tr>
    	</tr>

        <tr>
    			<td colspan="2">
    				<h3>Information complémentaire</h3>
    				<hr/>
    			</td>
		</tr>
		<tr>
			<td>
    			<label>Prenom</label>
    			<div>
    			    <img src="resources/img/Contact.png" alt="login" />
    			    <p>{$member->getFirstName()}</p>
    			</div>
    		</td>
			<td>
    			<label>Nom</label>
    			<div>
    			    <img src="resources/img/Contact.png" alt="login" />
    			    <p>{$member->getLastName()}</p>
    			</div>
    		</td>
    	</tr>
    	<tr>
			<td colspan="2">
    			<label>Anniversaire </label>
    			<div>
    			    <img id="pseudoLogo" src="resources/img/Birthday.png" alt="login" />
    			    <p>{$member->getBirthday()}</p>
    			</div>
    		</td>
    	</tr>
    </table>
    <button type='button' id='buttonDelete'>supprimer ce compte</button>
</form>
HTML;

//formulaire de changement de mot de passe
	$html .= <<<HTML
<form id="changeForm" name="change" method="POST" action="profil.php">
 <h2>Mot de passe</h2>
     <table>
		<tr>
            <td colspan="2">
                <label for="lastPass">Ancien mot de passe*</label>
                <div>
                    <img src="resources/img/Lock.png"/>
                    <input id="lastPass" name="lastPass" type="password">
                </div>
            </td>
        </tr>
		<tr>
            <td>
                <label for="newPass">Nouveau mot de passe*</label>
                <div>
                   <img src="resources/img/Lock.png"/>
                   <input id="newPass" name="pwd" type="password" onfocus="resetPWD()" onblur="verifyPassForm()">
                </div>
                <span id="erreurpass1"></span>
            </td>
             <td>
                <label for="newPassVerify">Confirmer le nouveau mot de passe*</label>
                <div>
                   <img src="resources/img/Lock.png"/>
					<input id="newPassVerify" name="pwdVerif" type="password" onfocus="resetPWD()" onblur="verifyPassForm()">
                </div>
                <span id="erreurpass"></span>
            </td>
        </tr>
    </table>
    <button type="button" onclick="sha256()">Confirmer</button>
    <p>* : champs obligatoires</p>
    <input id="lastPassHidden" name="lastPassHidden" type="hidden">
	<input id="newPassHidden" name="newPassHidden" type="hidden">
</form>
HTML;

	$webpage->appendJsUrl("js/inscription.js");
	$webpage->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js");

//boite de dialogue pour confirmer la suppression
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
}
