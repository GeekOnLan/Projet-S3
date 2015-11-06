function verifyInscription(){
	//si tout est bon on envoit
	mailvalid = validateEmail();
	passvalid = verifyPassword()
	pseu = document.getElementsByName('pseudo')[0].value;
	if (pseu != '') {
		if(!pseudo(pseu)){
			setInput('pseudo');
			setError('erreurpseudo', 'les caracteres autoriser sont : ');
		}
		else {
			xhr = new XMLHttpRequest();
			xhr.addEventListener('readystatechange', function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
					$xml = xhr.responseXML.documentElement.textContent;
					if ($xml == "false") {
						setInput('pseudo');
						setError('erreurpseudo', 'ce pseudo est deja pris');
					}
					else {
						if (passvalid && mailvalid) {
							ok = true;
							birth = document.getElementsByName('birthday')[0].value;
							if (birth != '' && !birthdayTest(birth)) {
								setInput('birthday');
								setError('erreurbirth', 'date de naissance au format : JJ/MM/AAAA');
								ok = false;
							}
							nom = document.getElementsByName('lastName')[0].value;
							if (nom != '' && !name(nom)) {
								setInput('lastname');
								setError('erreurlast', 'pas de caractere sepciaux');
								ok = false;
							}
							prenom = document.getElementsByName('firstName')[0].value;
							if (prenom != '' && !name(prenom)) {
								setInput('firstname');
								setError('erreurfirst', 'pas de caractere sepciaux');
								ok = false;
							}
							if (ok) {
								document.getElementsByName('hidden')[0].value = CryptoJS.SHA256(pass1);
								document.getElementsByName('pwd')[0].value = '';
								document.getElementsByName('pwdVerif')[0].value = '';
								/*document.getElementsByName('hiddenPseudo')[0].value = crypt(pseu);
								document.getElementsByName('pseudo')[0].value = '';*/
								document.inscription.submit();
							}
						}
					}
				}
			}, true);
			xhr.open('GET', 'scriptPHP/pseudoValide.php?pseudo=' + pseudo);
			xhr.send(null);
		}
	}
	else {
		setInput('pseudo');
		return false;
	}
}

//mais le contour en rouge
function setInput(name){
	document.getElementsByName(name)[0].style.borderColor = 'red';
}

//remet les coutours pas default
function resetInput(name){
	document.getElementsByName(name)[0].style.borderColor = '';
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
	pseu = document.getElementsByName('pseudo')[0].value;
	if (pseu != '') {
		if(!pseudo(pseu)){
			setInput('pseudo');
			setError('erreurpseudo', 'pas de caractere sepciaux');
		}
		else{
			xhr = new XMLHttpRequest();
			xhr.addEventListener('readystatechange', function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
					$xml = xhr.responseXML.documentElement.textContent;
					if ($xml == "false") {
						setInput('pseudo');
						setError('erreurpseudo', 'ce pseudo est déjà pris');
					}
					else
						resetPseudo();
				}
			}, true);
			xhr.open('GET', 'scriptPHP/pseudoValide.php?pseudo=' + pseu);
			xhr.send(null);
		}
	}
}

//teste pseudo et nom
function pseudo(name){
	re = /^[a-zA-Z][a-zA-Z0-9-_\.@]{1,20}$/;
	return re.test(name);
}

//----------------------------------------------------------//
//mail
//----------------------------------------------------------//

//verifier le mail pour le formulaire
function verifyMail(){
	mail = document.getElementsByName('mail')[0].value;
	if(mail != '' && !validateEmail(mail)){
		setInput('mail');
		setError('erreurmail', 'mail non valide');
	}
	else
		resetMail();
}

//valide un email en paramettre
function validateEmail(){
	mail = document.getElementsByName('mail')[0].value;
	re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if(!re.test(mail)){
		setInput('mail');
		setError('erreurmail', 'mail non valide');
		return false;
	}
	else
		return true;
}

//----------------------------------------------------------//
//prenom
//----------------------------------------------------------//

function verifyFirst(){
	first = document.getElementsByName('firstName')[0].value
	if(first != '' && !name(first)){
		setInput('firstName');
		setError('erreurfirst','pas de caractere sepciaux');
	}
	else
		resetLast();
}

function resetFirst(){
	resetInput('firstName');
	resetError('erreurfirst');
}

//teste prenom et nom
function name(name){
	re = /^[a-zA-Z]{0,20}$/;
	return re.test(name);
}

//----------------------------------------------------------//
//nom
//----------------------------------------------------------//

function verifyLast(){
	last = document.getElementsByName('lastName')[0].value
	if(last != '' && !name(last)){
		setInput('lastName');
		setError('erreurlast','pas de caractere sepciaux');
	}
	else
		resetLast();
}

function resetLast(){
	resetInput('lastName');
	resetError('erreurlast');
}

//----------------------------------------------------------//
//date de naissance
//----------------------------------------------------------//

//verify la date de naissance dans le formulaire
function verifyBirth(){
	birth = document.getElementsByName('birthday')[0].value
	if(birth != '' && !birthdayTest(birth)){
		setInput('birthday');
		setError('erreurbirth','date de naissance au format : JJ/MM/AAAA');
	}
	else
		resetBirth();
}

function resetBirth(){
	resetInput('birthday');
	resetError('erreurbirth');
}

//teste une date de naissance
function birthdayTest(dateBirth){
	re = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
	return re.test(dateBirth);
}

//----------------------------------------------------------//
//mot de passe
//----------------------------------------------------------//

//verifie les mots de passe pour le formulaire
function verifyPass(){
	pass1 = document.getElementsByName('pwd')[0].value;
	pass2 = document.getElementsByName('pwdVerif')[0].value;

	if(pass1 != ''){
		if(pass1 != pass2 && pass2 != ''){
			setInput('pwdVerif');
			setError('erreurpass', 'mot de passe different');
		}
		else if(!passwordTest(pass1)){
			setInput('pwd');
			setError('erreurpass1', 'le mot de passe doit contenir au moin une lettre majuscule un chiffre et au moins 6 characteres');
		}
		else{
			resetInput('pwdVerif')
			resetError('erreurpass');
		}
	}
	else{
		resetInput('pwdVerif')
		resetError('erreurpass');
		document.getElementsByName('pwdVerif')[0].value = '';
	}
}

//verifier les mots de passe
function verifyPassword(){
	pass1 = document.getElementsByName('pwd')[0].value;
	pass2 = document.getElementsByName('pwdVerif')[0].value;
	if(pass1 != '' && pass1 == pass2 && passwordTest(pass1))
		return true;
	else
		setInput('pwdVerif');
	setInput('pwd');
	return false;
}

//test un mot de passe
function passwordTest(pass){
	re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
	return re.test(pass);
}

//----------------------------------------------------------//
//reset
//----------------------------------------------------------//


function resetPWD(){
	resetError('erreurpass');
	resetError('erreurpass1');
	resetInput('pwdVerif');
	resetInput('pwd');
}

function resetMail(){
	resetError('erreurmail');
	resetInput('mail');
}

function resetPseudo(){
	resetError('erreurpseudo');
	resetInput('pseudo');
}

//----------------------------------------------------------//
//cryptage RSA
//----------------------------------------------------------//

function stringToASCII(chaine){
	res='';
	for(i = 0;i<chaine.length; i++){
		num = chaine.charCodeAt(i);
		if(num>=10 && num <=99)
			res+='0'+num;
		else if(num>=0 && num <=9)
			res+='00'+num;
		else
			res+=num;
	}
	while(res.length%4!=0)
		res='0'+res;
	return res;
}

function crypt(chaine){
	chaine = stringToASCII(chaine);
	console.log(chaine);
	var e = 3;
	var n = 33;
	res='';
	for(i = 0;i<chaine.length; i+=4){
		entier = '';
		entier += chaine.charAt(i);
		entier += chaine.charAt(i+1);
		entier += chaine.charAt(i+2);
		entier += chaine.charAt(i+3);
		temp = Math.pow(entier,e)%n;
		if(temp>=10 && temp <=99)
			temp ='00'+temp;
		else if(temp>=0 && temp <=9)
			temp ='000'+temp;
		else if(temp>=100 && temp <=999)
			temp = '0'+temp;
		res +=temp;
	}
	return res;
}