function sha256(){
    var pass = $("input[name='lastPass']").val();
    var newPass = $("input[name='newPass']").val();
    if(verifyPass()){
        $("input[name='lastPassHidden']").val(CryptoJS.SHA256(pass));
        $("input[name='newPassHidden']").val(CryptoJS.SHA256(newPass));
        $("input[name='lastPass']").val('');
        $("input[name='newPass']").val('');
        $("input[name='newPassVerify").val('');
        document.change.submit();
    }
}

//met le contour en rouge
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

//verifie les mots de passe pour le formulaire
function verifyPassForm(){
	var pass1 = document.getElementsByName('pwd')[0].value;
	var pass2 = document.getElementsByName('pwdVerif')[0].value;

	if(pass1 != ''){
		if(!passwordTest(pass1)){
			setInput('pwd');
			setError('erreurpass1', 'Le mot de passe doit contenir au moins une lettre majuscule, un chiffre et au moins 6 caractères');
		}
		else if(pass1 != pass2 && pass2 != ''){
			setInput('pwdVerif');
			setError('erreurpass', 'Les deux mots de passe ne coresspondent pas');
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
		setError('erreurpass1', 'Champs obligatoire');
		return false;
	}
	else if(pass1 != '' && !passwordTest(pass1)){
		setInput('pwd');
		setError('erreurpass1', 'Le mot de passe doit contenir au moins une lettre majuscule, un chiffre et au moins 6 caractères');
		return false;
	}
	else if(pass1 != '' && passwordTest(pass1) && pass2==''){
		setInput('pwdVerif');
		setError('erreurpass', 'Vous devez confirmer votre mot de passe');
		return false;
	}
	else if(pass1 != '' && pass2 ==''){
		setInput('pwdVerif');
		setError('erreurpass', 'Vous devez confirmer votre mot de passe');
		return false;
	}
}

//test un mot de passe
function passwordTest(pass){
	var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
	return re.test(pass);
}

function resetPWD(){
    resetError('erreurpass');
    resetError('erreurpass1');
    resetInput('pwdVerif');
    resetInput('pwd');
}