<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/deconnectedMember.inc.php');

$form = new GeekOnLanWebpage("GeekOnLan - Inscription");
$form->appendCssUrl("style/regular/inscription.css", "screen and (min-width: 680px");
$form->appendCssUrl("style/mobile/inscription.css", "screen and (max-width: 680px");

//On regarde si l'utilisateur � d�j� ex�cut� le formulaire
if (verify($_POST,"pseudo") && verify($_POST,"mail") && verify($_POST,"hiddenPass")) {
    try{
        $pseudo = $_POST['pseudo'];
        $mail = $_POST['mail'];
        $password = $_POST['hiddenPass'];
        $fN = null;
        $lN = null;
        $bD = null;

        //Test sur les champs non obligatoire :
        $cNO = verifyForm($_POST,"birthday","firstName","lastName");
        if($cNO==4){
            $fN = $_POST['firstName'];
            $lN = $_POST['lastName'];
            $bD = $_POST['birthday'];
            // On vérifie la validité du pseudonyme
            if(mb_ereg("^[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ \.]{0,40}$",$_POST['pseudo']) == 1) {
                //Connexion � la BdD
                $pdo = myPDO::getInstance();
                //Test pour vérifier si le pseudo n'est pas déjà utilisé
                $stmt = $pdo->prepare(<<<SQL
                SELECT pseudo
                FROM Membre
                WHERE pseudo = :pseudo;
SQL
                );
                $stmt->execute(array("pseudo" => $pseudo));
                $pseudoVerif = $stmt->fetch()['pseudo'];
                if ($pseudoVerif != $pseudo && strcasecmp($pseudo, "admin")!=0  && strcasecmp($pseudo, "administrateur")!=0 && strcasecmp($pseudo, "root")!=0) {
                    Member::createMember($pseudo,$mail,$password,$fN,$lN,$bD);
                    envoieMailValide($pseudo, $mail);
                    $form->appendContent("<p>Vous &#234;tes bien inscrit ! Vous allez recevoir un email de confirmation.</p>");
                }
                else {
                    $form->appendContent("<p>Le pseudonyme est déjà utilisé</p>");
                    addJsAndCss($form);
                    $form->appendContent(formulaire());
                }
            }
            else{
                $form->appendContent("<p>Le pseudonyme n'est pas valide</p>");
                addJsAndCss($form);
                $form->appendContent(formulaire());
            }
        }
        else {
            if ($cNO == 1) $form->appendContent("<p>Date d'anniversaire non valide !</p><br>");
            if ($cNO == 2) $form->appendContent("<p>Prénom non valide !</p><br>");
            if ($cNO == 3) $form->appendContent("<p>Nom non valide !</p><br>");
            addJsAndCss($form);
            $form->appendContent(formulaire());

        }
    }
    catch(Exception $e){
        $form->appendContent('<div>Un problème est survenu &nbsp; : ' . $e->getMessage() . '</div>');
    }
}

else{
    addJsAndCss($form);
    $form->appendContent(formulaire());
}
echo $form->toHTML();


// Fonction utilis� pour cr�e le formulaire d'inscription au sein de la page.
function formulaire(){
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
    				    <input id="pseudo" name="pseudo" type="text" placeholder="Pseudo" onfocus="resetPseudo()" onblur="verififyPseudoForm()">
    				</div>
    				<span id="erreurpseudo"></span>
    			</td>
    			<td>
    				<label for="pwd">Mot de passe*</label>
    				<div>
    				    <img src="resources/img/Lock.png" alt="login" />
    				    <input id="pwd" name="pwd" type="password" placeholder="Mot de passe" onfocus="resetPWD()" onblur="verifyPassForm()">
    				</div>
    				<span id="erreurpass1"></span>
    			</td>
    		</tr>
			<tr>
			    <td>
    				<label for="mail">E-Mail*</label>
    				<div>
    				    <img src="resources/img/Mail.png" alt="login" />
    				    <input id="mail" name="mail" type="text" placeholder="Adresse mail" onfocus="resetMail()" onblur="verifyMailForm()">
    				</div>
    				<span id="erreurmail"></span>
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
    				    <input id="firstName" name="firstName" type="text" placeholder="Prénom" onfocus="resetFirst()" onblur="verifyFirstForm()">
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
    				    <input id="lastName" name="lastName" type="text" placeholder="Nom" onfocus="resetLast()" onblur="verifyLastForm()">
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
    return $html;

}
// Préparation du mail contenant le lien d'activation
function envoieMailValide($login,$email){
    //génération aléatoire d'une clé
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

function addJsAndCss(GeekOnLanWebpage $form){
    $form->appendJsUrl("js/rsa.js");
    $form->appendJsUrl("js/BigInt.js");
    $form->appendJsUrl("js/inscription.js");
}
