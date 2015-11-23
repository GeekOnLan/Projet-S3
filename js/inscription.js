var xhr;
if (window.XMLHttpRequest) {
	xhr = new XMLHttpRequest();
} else {
	// code for IE6, IE5
	xhr = new ActiveXObject("Microsoft.XMLHTTP");
}

window.addEventListener("keypress",function(even){
	if(even.keyCode === 13)
		verifyInscription();
});

function verifyInscription(){
	//si tout est bon on envoit
	var pseudo = verififyPseudo();
	var mail = verifyMail();
	var first =  verifyFirst();
	var last =  verifyLast();
	var birth = verifyBirth();
	var pass = verifyPass();
	if(pseudo){
		xhr.abort();
		xhr.addEventListener('readystatechange', function () {
			if (xhr.readyState === 4 && xhr.status === 200) {
				var xml = xhr.responseXML.getElementsByTagName('response').item(0).textContent;
				if (xml == "false") {
					setInput('pseudo');
					setError('erreurpseudo', 'ce pseudo est déjà pris');
					pseudo=false;
				}
				else {
					resetPseudo();
					if (pseudo && first && last && mail && birth && pass) {
						pass1 = document.getElementsByName('pwd')[0].value;
						document.getElementsByName('hiddenPass')[0].value = CryptoJS.SHA256(pass1);
						document.getElementsByName('pwd')[0].value = '';
						document.getElementsByName('pwdVerif')[0].value = '';
						document.inscription.submit();
					}
				}
			}
		}, true);
		xhr.open('GET', 'scriptPHP/pseudoValide.php?wait&pseudo=' + document.getElementsByName('pseudo')[0].value);
		xhr.send(null);
	}
}

//met les erreur sur le css
function setInput(name){
	document.getElementsByName(name)[0].setCustomValidity("Champ invalide")
}

//remet les coutours pas default
function resetInput(name){
	document.getElementsByName(name)[0].setCustomValidity("");
}

//affiche un message d'erreur
function setError(name,error){
	document.getElementById(name).innerHTML = error;
}
//enleve les erreurs
function resetError(name){
	document.getElementById(name).innerHTML = '';
}


//----------------------------------------------------------//
//pseudo
//----------------------------------------------------------//

//verifier le pseudo avec ajax pour le formulaire
function verififyPseudoForm() {
	var pseu = document.getElementsByName('pseudo')[0].value;
	if (pseu != '') {
		if(pseu.length>20){
			setInput('pseudo');
			setError('erreurpseudo', 'peudo trop grand');
		}
		else if(!pseudo(pseu)){
			setInput('pseudo');
			setError('erreurpseudo', 'pas de caractere speciaux');
		}
		else{
			xhr.abort();
			xhr.addEventListener('readystatechange', function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
					var xml = xhr.responseXML.getElementsByTagName('response').item(0).textContent;
					if (xml == "false") {
						setInput('pseudo');
						setError('erreurpseudo', 'ce pseudo est déjà pris');
					}
					else
						resetPseudo();
				}
			}, true);
			xhr.open('GET', 'scriptPHP/pseudoValide.php?wait&pseudo=' + pseu);
			xhr.send(null);
		}
	}
}

function verififyPseudo() {
	var pseu = document.getElementsByName('pseudo')[0].value;
	if (pseu != '') {
		if(pseu.length>20){
			setInput('pseudo');
			setError('erreurpseudo', 'pseudo trop grand');
			return false;
		}
		else if(!pseudo(pseu)){
			setInput('pseudo');
			setError('erreurpseudo', 'pas de caractere speciaux');
			return false;
		}
		else{
			resetPseudo();
			return true;
		}
	}
	else {
		setInput('pseudo');
		setError('erreurpseudo', 'champ obligatoire');
		return false;
	}
}

//teste pseudo
function pseudo(name){
	var re = /^[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ \.]{0,40}$/;
	return re.test(name);
}

//----------------------------------------------------------//
//mail
//----------------------------------------------------------//

//verifier le mail pour le formulaire
function verifyMailForm(){
	var mail = document.getElementsByName('mail')[0].value;
	if(mail != '' && !validateEmail(mail)){
		setInput('mail');
		setError('erreurmail', 'mail non valide');
	}
	else
		resetMail();
}

function verifyMail(){
	var mail = document.getElementsByName('mail')[0].value;
	if(mail == ''){
		setInput('mail')
		setError('erreurmail', 'champ obligatoire');
		return false;
	}
	else {
		if (validateEmail()) {
			resetMail();
			return true;
		}
		else {
			setInput('mail');
			setError('erreurmail', 'mail non valide');
			return false;
		}
	}
}

//valide un email en paramettre
function validateEmail(){
	var mail = document.getElementsByName('mail')[0].value;
	var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if(!re.test(mail))
		return false;
	else
		return true;
}

//----------------------------------------------------------//
//prenom
//----------------------------------------------------------//

function verifyFirstForm(){
	var first = document.getElementsByName('firstName')[0].value;
	if(first != '' && !name(first)){
		setInput('firstName');
		setError('erreurfirst','Prénom incorrect');
	}
	else
		resetFirst();
}

function verifyFirst(){
	var first = document.getElementsByName('firstName')[0].value;
	if(first == '')
		return true;
	else if(!name(first)){
		setInput('firstName');
		setError('erreurfirst','Prénom incorrect');
		return false;
	}
	else {
		resetFirst();
		return true;
	}
}

//teste prenom et nom
function name(name){
	var re = /^[a-zA-Z]{1,20}$/;
	return re.test(name);
}

//----------------------------------------------------------//
//nom
//----------------------------------------------------------//

function verifyLastForm(){
	var last = document.getElementsByName('lastName')[0].value;
	if(last != '' && !name(last)){
		setInput('lastName');
		setError('erreurlast','Nom incorrect');
	}
	else
		resetLast();
}

function verifyLast(){
	var last = document.getElementsByName('lastName')[0].value;
	if(last == '')
		return true;
	else if(!name(last)){
		setInput('lastName');
		setError('erreurlast','Nom incorrect');
		return false;
	}
	else {
		resetLast();
		return true;
	}
}

//----------------------------------------------------------//
//date de naissance
//----------------------------------------------------------//

//verify la date de naissance dans le formulaire
function verifyBirthForm(){
			var d = document.getElementsByName('birthday')[0].value;
			if(d != '' && !birthdayTest(d)){
				setInput('birthday');
				setError('erreurbirth','date de naissance au format : JJ/MM/AAAA');
			}
			else if(d != ''){
				var j=(d.substring(0,2));
				var m=(d.substring(3,5));
				var a=(d.substring(6));
				var d2=new Date(a,m-1,j);
				var j2=d2.getDate();
				var m2=d2.getMonth()+1;
				var a2=d2.getFullYear();
				if (a2<=100) {a2=1900+a2}
				if ( (j!=j2)||(m!=m2)||(a!=a2)){
					setInput('birthday');
					setError('erreurbirth','cette date n\'existe pas');
				}
			else
				resetBirth();
	}
}

function verifyBirth(){
	var d = document.getElementsByName('birthday')[0].value;
	if(d=='')
		return true;
	if(d != '' && !birthdayTest(d)){
		setInput('birthday');
		setError('erreurbirth','date de naissance au format : JJ/MM/AAAA');
		return false;
	}
	else if(d != ''){
		var j=(d.substring(0,2));
		var m=(d.substring(3,5));
		var a=(d.substring(6));
		var d2=new Date(a,m-1,j);
		var j2=d2.getDate();
		var m2=d2.getMonth()+1;
		var a2=d2.getFullYear();
		if (a2<=100) {a2=1900+a2}
		if ( (j!=j2)||(m!=m2)||(a!=a2)){
			setInput('birthday');
			setError('erreurbirth','cette date n\'existe pas');
			return false;
		}
		else {
			resetBirth();
			return true;
		}
	}
}

//teste une date de naissance
function birthdayTest(dateBirth){
	var re = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
	return re.test(dateBirth);
}

//----------------------------------------------------------//
//mot de passe
//----------------------------------------------------------//

//verifie les mots de passe pour le formulaire
function verifyPassForm(){
	var pass1 = document.getElementsByName('pwd')[0].value;
	var pass2 = document.getElementsByName('pwdVerif')[0].value;

	if(pass1 != ''){
		if(!passwordTest(pass1)){
			setInput('pwd');
			setError('erreurpass1', 'le mot de passe doit contenir au moin une lettre majuscule un chiffre et au moins 6 characteres');
		}
		else if(pass1 != pass2 && pass2 != ''){
			setInput('pwdVerif');
			setError('erreurpass', 'mot de passe different');
		}
		else{
			resetInput('pwdVerif');
			resetError('erreurpass');
		}
	}
	else{
		resetPWD();
		document.getElementsByName('pwdVerif')[0].value = '';
	}
}

//verifier les mots de passe
function verifyPass(){
	var pass1 = document.getElementsByName('pwd')[0].value;
	var pass2 = document.getElementsByName('pwdVerif')[0].value;
	if(pass1 != '' && pass1 == pass2 && passwordTest(pass1)){
		resetPWD();
		return true;
	}
	else if(pass1 == ''){
		setInput('pwd');
		setError('erreurpass1', 'champ obligatoire');
		return false;
	}
	else if(pass1 != '' && !passwordTest(pass1)){
		setInput('pwd');
		setError('erreurpass1', 'le mot de passe doit contenir au moin une lettre majuscule un chiffre et au moins 6 characteres');
		return false;
	}
	else if(pass1 != '' && passwordTest(pass1) && pass2==''){
		setInput('pwdVerif');
		setError('erreurpass', 'confirmer votre mot de passe');
		return false;
	}
	else if(pass1 != '' && pass2 ==''){
		setInput('pwdVerif');
		setError('erreurpass', 'confirmer votre mot de passe');
		return false;
	}
}

//test un mot de passe
function passwordTest(pass){
	var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
	return re.test(pass);
}

//----------------------------------------------------------//
//reset
//----------------------------------------------------------//

function resetPseudo(){
	resetError('erreurpseudo');
	resetInput('pseudo');
}


function resetMail(){
	resetError('erreurmail');
	resetInput('mail');
}

function resetFirst(){
	resetInput('firstName');
	resetError('erreurfirst');
}

function resetLast(){
	resetInput('lastName');
	resetError('erreurlast');
}

function resetBirth(){
	resetInput('birthday');
	resetError('erreurbirth');
}

function resetPWD(){
	resetError('erreurpass');
	resetError('erreurpass1');
	resetInput('pwdVerif');
	resetInput('pwd');
}

