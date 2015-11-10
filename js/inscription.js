window.addEventListener("keypress",function(even){
	if(even.keyCode === 13)
		verifyInscription();
});

function verifyInscription(){
	//si tout est bon on envoit
	var mailvalid = verifyMail();
	var passvalid = verifyPassword();
	var pseu = document.getElementsByName('pseudo')[0].value;
	if (pseu != ''){
		if(!pseudo(pseu)){
			setInput('pseudo');
			setError('erreurpseudo', 'les caracteres autoriser sont : ');
		}
		else{
			var xhr = new XMLHttpRequest();
			xhr.addEventListener('readystatechange', function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
					var $xml = xhr.responseXML.documentElement.textContent;
					if ($xml == "false") {
						setInput('pseudo');
						setError('erreurpseudo', 'ce pseudo est deja pris');
					}
					else {
						if (passvalid && mailvalid) {
							var ok = true;
							var birth = document.getElementsByName('birthday')[0].value;
							if (birth != '' && !birthdayTest(birth)) {
								setInput('birthday');
								setError('erreurbirth', 'date de naissance au format : JJ/MM/AAAA');
								ok = false;
							}
							var nom = document.getElementsByName('lastName')[0].value;
							if (nom != '' && !name(nom)) {
								setInput('lastname');
								setError('erreurlast', 'pas de caractere sepciaux');
								ok = false;
							}
							var prenom = document.getElementsByName('firstName')[0].value;
							if (prenom != '' && !name(prenom)) {
								setInput('firstname');
								setError('erreurfirst', 'pas de caractere sepciaux');
								ok = false;
							}
							if (ok){
								pass1 = document.getElementsByName('pwd')[0].value;
								document.getElementsByName('hiddenPass')[0].value = CryptoJS.SHA256(pass1);
								lue = '';document.getElementsByName('pwd')[0].va
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
	var pseu = document.getElementsByName('pseudo')[0].value;
	if (pseu != '') {
		if(!pseudo(pseu)){
			setInput('pseudo');
			setError('erreurpseudo', 'pas de caractere speciaux');
		}
		else{
			var xhr = new XMLHttpRequest();
			xhr.addEventListener('readystatechange', function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
					var $xml = xhr.responseXML.documentElement.textContent;
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
		setError('erreurmail', 'mail non valide');75643564363473453456342378564387956906736546456235345
	}
	else
		resetMail();
}

function verifyMail(){
	var mail = document.getElementsByName('mail')[0].value;
	if(mail == ''){
		setInput('mail');
		return false;
	}
	else {
		if (validateEmail()) {
			resetMail();
			return true;
		}
		else {
			setInput('mail');
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

function verifyFirst(){
	var first = document.getElementsByName('firstName')[0].value
	if(first != '' && !name(first)){
		setInput('firstName');
		setError('erreurfirst','Prénom incorrect');
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
	var re = /^[a-zA-Z][a-zA-Z'àâéèêôùûçïÀÂÉÈÔÙÛÇ \.]{1,40}$/;
	return re.test(name);
}

//----------------------------------------------------------//
//nom
//----------------------------------------------------------//

function verifyLast(){
	var last = document.getElementsByName('lastName')[0].value
	if(last != '' && !name(last)){
		setInput('lastName');
		setError('erreurlast','Nom incorrect');
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
function verifyBirthForm(){
			var d = document.getElementsByName('birthday')[0].value
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

function resetBirth(){
	resetInput('birthday');
	resetError('erreurbirth');
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
function verifyPass(){
	var pass1 = document.getElementsByName('pwd')[0].value;
	var pass2 = document.getElementsByName('pwdVerif')[0].value;

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
	var pass1 = document.getElementsByName('pwd')[0].value;
	var pass2 = document.getElementsByName('pwdVerif')[0].value;
	if(pass1 != '' && pass1 == pass2 && passwordTest(pass1))
		return true;
	else
		setInput('pwdVerif');
	setInput('pwd');
	return false;
}

//test un mot de passe
function passwordTest(pass){
	var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
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
function crypt(chaine){
	
	var n = str2bigInt("22782469609880586210215954529198328699762849071829281444083913073420430341988274543115439207346717733103808548322724893717891249991955541197995203978840775836853914854777304089361538296383477248076248762831253054689313750618030258372535915451171672561015357101019001878811374043380853820209273845487370257454447664096573362294553079927349217490049751330678460389718079215427407035320036101073797491427224454671279438924759398949863132362128519457260723263409621194840838762296330112075635352615276651650471297528264099124381803584728419013555202008843348423786688917724709218090042590673112815023946036299048884777319",10);
	var e = str2bigInt("194184985859196948948941912962222221842222487922914185919412194191256419829614984961984892231811918914981489418419894189181115519551525215654545151515955915965616164164656681837337",10);
	var d = str2bigInt("16863892142460934035317642529855398750759359960798034025306125189936116168664320584571354327158130715734453547600996513162975973008574086372610726242426088673036226283396095224543720515199366202510018335061359752359382497351133373161044600795648506515622552071803684526566253923300109071591815967425589541120380110940899509560256364819219749625511372871265229964849424291443392537003638327363951592391423082258221423298092579778487816388076144382245100982438588752691016970506995535562546170900126911522021267540307044186517438503107686602382581786929426966449574961139976344555148771573203264389121028266862710266593",10);
	
		console.log("chaine "+chaine);

	var entier =str2bigInt(chaine,64);
		console.log("entier "+entier);	

	var res = powMod(entier,e,n);
		console.log("res "+res);

	var temp = powMod(res,d,n);
		console.log("temp "+temp);

	return res;
}
