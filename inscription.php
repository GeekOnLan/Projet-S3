<?php

require_once('includes/autoload.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/requestUtils.inc.php');
require_once('includes/deconnectedMember.inc.php');

/**
 * Vérifie la validité de pseudo fourni par l'utilisateur. Vérifie entre autre
 * si le pseudo contient des caractères spéciaux, un nom interdit ou est déjà utilisé
 *
 * @param string $pseudo - Le pseudo à vérifier
 * @return string L'éventuelle message d'erreur
 */
function verifPseudo($pseudo) {
	$message = "";
	if(mb_ereg("^[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ \.]{0,40}$", $pseudo) == 0)
		$message = "Votre pseudo contient des caractères spéciaux";
	else if(preg_match("/(r[0o]{2,40}t)|(a+d+m+i+n+)/i", $pseudo) == 1)
		$message = "Ce pseudo est interdit";
	else {
		$pseudoVerif = selectRequest(array("pseudo" => $pseudo), array(PDO::FETCH_BOTH => null), "pseudo", "Membre", "pseudo = :pseudo");
		if(isset($pseudoVerif[0]))
			$message = "Ce pseudo est déjà utilisé";
	}

	return $message;
}

/**
 * Verifie la validité des champs optionnels saisis. Test entre autre
 * la validité de la date, du nom et du prénom
 *
 * @param string $birthday
 * @param string $firstName
 * @param string $lastName
 * @return string Le message d'erreur si une erreur survient
 */
function verifyOption($birthday, $firstName, $lastName){
	if($birthday != null) {
		$date = explode('/', $birthday);
		$day = isset($date[0]) ? intval($date[0]) : null;
		$month = isset($date[1]) ? intval($date[1]) : null;
		$year = isset($date[2]) ? intval($date[2]) : null;

		if(!checkdate($month, $day, $year))
			return "Date d'anniversaire invalide";
	}

	if($firstName != null && !mb_ereg("^[a-zA-Z\ ]+$", $firstName) == 1)
		return "Prénom invalide";

	if($lastName != null && !mb_ereg("^[a-zA-Z\ ]+$", $lastName) == 1)
		return "Nom invalide";

	return "";
}

/**
 * Envois un mail de confirmation à l'utilisateur nouvellement créé
 *
 * @param string $login - Pseudo du membre
 * @param string $email - E-mail du membre
 */
function envoieMailValide($login, $email){
	// Génération aléatoire d'une clé
	$key = md5(microtime(TRUE)*100000);

	// Insertion de la clé dans la base de données
	$dbh = myPDO::GetInstance();
	$stmt = $dbh->prepare("UPDATE Membre SET cleMail=:key WHERE pseudo like :login");
	$stmt->bindParam(':key', $key);
	$stmt->bindParam(':login', $login);
	$stmt->execute();

	$destinataire = $email;
	$sujet = "Activation de votre compte sur geekonlan" ;
	$entete = "From: inscription@geekonlan.com" ;
	$key = urlencode($key);
	$login = urlencode($login);
	// Le lien d'activation est composé du login(login) et de la clé(key)
	$message =<<<HTML
Bienvenue sur GeekOnLAN,

Pour activer votre compte, veuillez cliquer sur le lien ci dessous
ou le copier/coller dans votre navigateur internet.

http://geekonlan.com/activation.php?log={$login}&key={$key}


---------------
Ceci est un mail automatique, Merci de ne pas y répondre

HTML;

	mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail
}

$html = "";

// Vérification du formulaire
if(verify($_POST, "pseudo") && verify($_POST, "mail") && verify($_POST, "hiddenPass")) {
	$pseudo 	= $_POST['pseudo'];
	$mail 		= $_POST['mail'];
	$password 	= $_POST['hiddenPass'];
	$firstName 	= verify($_POST, "firstName") ? $_POST['firstName'] : null;
	$lastName 	= verify($_POST, "lastName") ? $_POST['lastName'] : null;
	$birthday 	= verify($_POST, "bithday") ? $_POST['bithday'] : null;

	$resPseudo = verifPseudo($pseudo);
	$resOption = verifyOption($birthday, $firstName, $lastName);

	if($resPseudo == "" && $resOption == "") {
		Member::createMember($pseudo, $mail, $password, $firstName,$lastName, $birthday);
		envoieMailValide($pseudo, $mail);
		header('Location: message.php?message=Vous etes bien inscrit ! Vous allez recevoir un email de confirmation');
	} else
		$html = "<p>$resPseudo $resOption</p>";
}

$page = new GeekOnLanWebpage("GeekOnLan - Inscription");
$page->appendJsUrl("js/rsa.js");
$page->appendJsUrl("js/BigInt.js");
$page->appendJsUrl("js/inscription.js");
$page->appendCssUrl("style/regular/inscription.css", "screen and (min-width: 680px");
$page->appendCssUrl("style/mobile/inscription.css", "screen and (max-width: 680px");

$html= <<<HTML
	<form method="POST" name="inscription" action="inscription.php">
		<h2>Inscription</h2>
		<table>
			<tr>
    			<td colspan="2">
    				<h3>Information du compte</h3>
    				<hr/>
    			</td>
			</tr>
			<tr>
				<td>
    				<label for="pseudo">Pseudonyme*</label>
    				<div>
    				    <img id="pseudoLogo" src="resources/img/Contact.png" alt="login" />
    				    <input id="pseudo" name="pseudo" type="text" placeholder="Pseudo" maxlength="31" onfocus="resetPseudo()" onblur="verififyPseudoForm()">
    				</div>
    				<span id="erreurpseudo"></span>
    			</td>
    			<td>
    				<label for="mail">E-Mail*</label>
    				<div>
    				    <img src="resources/img/Mail.png" alt="login" />
    				    <input id="mail" name="mail" type="text" placeholder="Adresse mail" maxlength="64" onfocus="resetMail()" onblur="verifyMailForm()">
    				</div>
    				<span id="erreurmail"></span>
    			</td>
    		</tr>
			<tr>
				<td>
					<label for="pwd">Mot de passe*</label>
    				<div>
    				    <img src="resources/img/Lock.png" alt="login" />
    				    <input id="pwd" name="pwd" type="password" placeholder="Mot de passe" onfocus="resetPWD()" onblur="verifyPassForm()">
    				</div>
    				<span id="erreurpass1"></span>
    			</td>
			    <td>
    				<label for="pwdVerif">Confirmer mot de passe*</label>
    				<div>
    				    <img src="resources/img/Lock.png" alt="login" />
    				    <input id="pwdVerif" name="pwdVerif" type="password" placeholder="Mot de passe" onfocus="resetPWD()" onblur="verifyPassForm()">
    				</div>
    				<span id="erreurpass"></span>
    			</td>


    		</tr>
    		<tr>
    			<td colspan="2">
    				<h3>Information personnelles</h3>
    				<hr/>
    			</td>
			</tr>
			<tr>
    			<td>
    				<label for="birthday">Date de naissance</label>
    				<div>
    				    <img src="resources/img/Birthday.png" alt="login" />
    				    <input id="birthday" name="birthday" placeholder="Date de naissance" onfocus="resetBirth()" onblur="verifyBirthForm()" type="text">
    				</div>
    				<span id="erreurbirth"></span>
    			</td>
    			<td rowspan="3">
    				<p>
    					En vous inscrivant vous vous engagez à respecter le réglement de GeekOnLan ainsi que Lorem ipsum dolor sit amet,
						<br/>
						<br/>
    					consectetur adipiscing elit. Nullam sit amet porttitor arcu. Donec tempor, enim lacinia vehicula commodo, velit ante facilisis orci,
    					eu pretium erat massa non elit. In porta ut nulla non rutrum. Curabitur condimentum nunc vitae ante pretium.

    					<br/>
    					<br/>
    					* : champs obligatoires
					</p>
    			</td>
    		</tr>
			<tr>
				<td>
    				<label for="firstName">Prénom</label>
    				<div>
    				    <img src="resources/img/Contact.png" alt="login" />
    				    <input id="firstName" name="firstName" type="text" placeholder="Prénom" maxlength="31" onfocus="resetFirst()" onblur="verifyFirstForm()">
                    </div>
    				<span id="erreurfirst"></span>
    			</td>
    			<td></td>
    		</tr>
			<tr>
				<td>
    				<label for="lastName">Nom</label>
    				<div>
    				    <img src="resources/img/Contact.png" alt="login" />
    				    <input id="lastName" name="lastName" type="text" placeholder="Nom" maxlength="31" onfocus="resetLast()" onblur="verifyLastForm()">
    				</div>
    				<span id="erreurlast"></span>
    			</td>
    			<td></td>
    		</tr>
			<div>
    			<input name="hiddenPass" type="hidden">
    		</div>
			<div>
    			<input name="hiddenPseudo" type="hidden">
    		</div>
		</table>
		<button type="button" onclick="verifyInscription()"> Envoyer </button>
	</form>
HTML;

$page->appendContent($html);

echo $page->toHTML();
