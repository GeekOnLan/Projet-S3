function verifyInscription(){
	pseudo = document.getElementsByName('pseudo')[0].value;
	mail = document.getElementsByName('mail')[0].value;
	pass1 = document.getElementsByName('pwd')[0].value;
	pass2 = document.getElementsByName('pwdVerif')[0].value;
	
	if(pseudo=='' || mail=='' || (pass1 != pass2)){
		if(pseudo=='')
			document.getElementsByName('pseudo')[0].style.borderColor = 'red';
		if(mail=='')
			document.getElementsByName('mail')[0].style.borderColor = 'red';
		if(pass1 != pass2){
			document.getElementsByName('pwd')[0].style.borderColor = 'red';
			document.getElementsByName('pwdVerif')[0].style.borderColor = 'red';
			document.getElementsByName('pwd')[0].value = ''
			document.getElementsByName('pwdVerif')[0].value = '';
		}
	}
	else
		document.getElementsByName('hidden')[0].value = CryptoJS.SHA256(pass1);
	document.getElementsByName('pwd')[0].value = '';
	document.getElementsByName('pwdVerif')[0].value ='';
		document.inscription.submit();
}

function resetInput(name){
    document.getElementsByName(name)[0].style.borderColor = 'white';
}

function verifyPass(){
	pass1 = document.getElementsByName('pwd')[0].value;
	pass2 = document.getElementsByName('pwdVerif')[0].value;
	if(pass1 != pass2)
		document.getElementsByName('pwdVerif')[0].style.borderColor = 'red';
	else
		document.getElementsByName('pwdVerif')[0].style.borderColor = 'blue';
}