function verifyInscription(){
	mail = document.getElementsByName('mail')[0].value;
	//si tout est bon on envoit
	mailvalid = validateEmail(mail);
	pseudoValid = verifyPseudo();
	if(verifyPassword() && mailvalid && pseudoValid){
		document.getElementsByName('hidden')[0].value = CryptoJS.SHA256(pass1);
		document.getElementsByName('pwd')[0].value = '';
		document.getElementsByName('pwdVerif')[0].value ='';
		document.inscription.submit();
	}
}

function setInput(name){
	document.getElementsByName(name)[0].style.borderColor = 'red';
}

//remet les coutours pas default
function resetInput(name){
	document.getElementsByName(name)[0].style.borderColor = '';
}

function setError(name,error){
	document.getElementById(name).innerHTML = error;
}
//enleve les erreurs
function resetError(name){
	document.getElementById(name).innerHTML = '';
}

//verifie les mots de passe pour le formulaire
function verifyPass(){
	pass1 = document.getElementsByName('pwd')[0].value;
	pass2 = document.getElementsByName('pwdVerif')[0].value;
	if(pass1 != ''){
		if(pass1 != pass2 && pass2 != ''){
			setInput('pwdVerif');
			setError('erreurpass', 'mot de passe different');
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
	if(pass1 != '' && pass1 == pass2)
		return true;
	else
		setInput('pwdVerif');
		setInput('pwd');
	return false;
}

//verifier le mail pour le formulaire
function verifyMail(){
	mail = document.getElementsByName('mail')[0].value;
	if(mail != '' && !validateEmail(mail)){
		setInput('mail');
		setError('erreurmail', 'mail non valide');
	}
	else{
		resetInput('mail');
		resetError('erreurmail');
	}
}

function validateEmail(email){
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    if(!re.test(email)){
    	setInput('mail');
    	return false;
    }
    else
    	return true;
}

function verifyPseudo(){
	pseudo = document.getElementsByName('pseudo')[0].value;
	if(pseudo == ''){
		setInput('pseudo');
		return false;
	}
	return true;
}

//verifier le pseudo avec ajax
function verififyPseudoForm(){
	pseudo = document.getElementsByName('pseudo')[0].value;
	if(pseudo != '') {
		xhr = new XMLHttpRequest();
		xhr.addEventListener('readystatechange', function () {
			if (xhr.readyState === 2) {
				console.log("send");
			}
			if (xhr.readyState === 4  && xhr . status ===  200 ){
				cels = xhr.responseXML.documentElement;
				alert(cels);
			}
		}, true);
		xhr.open('GET','pseudoValide.php?pseudo='+pseudo);
		xhr.send(null);
	}
}