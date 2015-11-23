window.addEventListener("keypress",function(even){
	if(even.keyCode === 13)
		verifyInscription();
});

function getAllError(){
	//str1.concat(str2)
	var error = document.getElementById("erreurNameLAN").innerHTML;
	error+=document.getElementById("erreurDateLAN").innerHTML;
	error+=document.getElementById("erreurDescriptionLAN").innerHTML;
	error+=document.getElementById("erreurVilleLAN").innerHTML;
	error+=document.getElementById("erreurAdresseLAN").innerHTML;
	return error;
}

function verifyLAN(){
	//si tout est bon on envoit
	var erreur = getAllError();
	if(erreur=="     "){
		console.log("pas d'erreurs");
		var nameLAN = document.getElementsByName('nameLAN')[0].value;
		var dateLAN = document.getElementsByName('dateLAN')[0].value;
		var descriptionLAN =  document.getElementsByName('descriptionLAN')[0].value;
		var ville = document.getElementsByName('villeLAN')[0].value;
		var adresseLAN = document.getElementsByName('adresseLAN')[0].value;
		document.ajoutLAN.submit();
	}else{
		console.log("erreur champs");
	}
}

//mais le contour en rouge
function voidRedInput(name){
	document.getElementsByName(name)[0].setCustomValidity("Champ invalide");
}

//remet les coutours pas default
function resetVoidRedInput(name){
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
//nameLAN
//----------------------------------------------------------//
function nomLanDejaUtilise(){
	/*
	var xhr = new XMLHttpRequest();
	xhr.addEventListener('readystatechange', function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			var xml = xhr.responseXML.getElementsByTagName('response').item(0).textContent;
			if (xml == "false") {
				voidRedInput('nameLAN');
				setError('erreurNameLAN', 'ce nom de LAN est déjà pris');
			}
			else
				resetPseudo();
		}
	}, true);
	xhr.open('GET', 'scriptPHP/pseudoValide.php?pseudo=' + pseu);
	xhr.send(null);
	*/
	return false;
}


//verifier le pseudo avec ajax pour le formulaire
function verifyNameLAN() {
	var nameLAN = document.getElementsByName('nameLAN')[0].value;

	var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ]{0,30}/);
	match = regex.exec(nameLAN)==nameLAN;
	if(!match){
		voidRedInput('nameLAN');
		setError('erreurNameLAN', 'caractères non autorisé utilisé');
	}else if (nameLAN.length>2) {
		if(nameLAN.length>31){
			voidRedInput('nameLAN');
			setError('erreurNameLAN', 'nom de LAN trop grand ');
		}else if(nomLanDejaUtilise()){
			voidRedInput('nameLAN');
			setError('erreurNameLAN', 'nom de LAN deja utilisé');
		}else{
			setError('erreurNameLAN',' ');
			console.log("nom OK");
		}
	}else{
		voidRedInput('nameLAN');
		setError('erreurNameLAN', 'nom de LAN trop petit');
	}
}
//----------------------------------------------------------//
//date de LAN
//----------------------------------------------------------//

//verify la date de naissance dans le formulaire
function verifyDateLAN(){
	var d = document.getElementsByName('dateLAN')[0].value;
	if(d!=''){
		var j=(d.substring(0,2));
		var m=(d.substring(3,5));
		var a=(d.substring(6));
		m-=1;
		var d=new Date(a,m,j);
		if(d<new Date()){
			voidRedInput('dateLAN');
			setError('erreurDateLAN','vous ne pouvez retourner dans le passé, vous vous-êtes pris pour Marty ?');
		}else{
			var rep= (d.getFullYear()!=a || d.getMonth()!=m) ? "date invalide" : "date valide";

			if (rep=="date invalide"){
				voidRedInput('dateLAN');
				setError('erreurDateLAN','cette date n\'existe pas');
			}else{
				setError('erreurDateLAN',' ');
				console.log(rep);
			}
		}
	}else{
		voidRedInput('dateLAN');
		setError('erreurDateLAN','veillez entrer une date sous la forme jj/mm/yyyy');
	}
}


//----------------------------------------------------------//
//description
//----------------------------------------------------------//

//verifier le mail pour le formulaire
function verifyDescriptionLAN(){
	var description = document.getElementsByName('descriptionLAN')[0].value;

	var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ]{0,30}/);
	match = regex.exec(description)==description;
	if(!match){
		document.getElementsByName('descriptionLAN')[0].value="LAN créer par";
		setError('erreurDescriptionLAN', 'caractères non autorisé utilisé');
	}else if(description.length>80){
		voidRedInput('descriptionLAN');
		setError('erreurDescriptionLAN', 'description trop longue');
	}
	else{
		setError('erreurDescriptionLAN',' ');
		console.log("description ok");
	}
}

//----------------------------------------------------------//
//ville
//----------------------------------------------------------//

function valideVille(ville){
	var res=false;
	if(ville!="")res=true;
	return res;
}

function verifyVilleLAN(){
	var ville = document.getElementsByName('villeLAN')[0].value;

	var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ]{0,30}/);
	match = regex.exec(ville)==ville;
	if(!match){
		voidRedInput('villeLAN');
		setError('erreurVilleLAN', 'caractères non autorisé utilisé');
	}else if(valideVille(ville)){
		setError('erreurVilleLAN',' ');
		console.log("ville ok");
	}
	else{
		voidRedInput('villeLAN');
		setError('erreurVilleLAN','Ville incorrect');
	}
}

//----------------------------------------------------------//
//adresse
//----------------------------------------------------------//

function valideAdresse(adresse){
	var res=false;
	if(adresse!="")res=true;
	return res;
}

function verifyAdresseLAN(){
	var adresse = document.getElementsByName('adresseLAN')[0].value

	var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ]{0,30}/);
	match = regex.exec(adresse)==adresse;
	if(!match){
		voidRedInput('adresseLAN');
		setError('erreurAdresseLAN', 'caractères non autorisé utilisé');
	}else if(valideAdresse(adresse)){
		setError('erreurAdresseLAN',' ');
		console.log("Adresse ok");
	}
	else{
		voidRedInput('adresseLAN');
		setError('erreurAdresseLAN','Adresse incorrect');
	}
}


//----------------------------------------------------------//
//reset
//----------------------------------------------------------//

function resetNameLAN(){
	resetError('erreurNameLAN');
	resetVoidRedInput('nameLAN');
}


function resetDateLAN(){
	resetError('erreurDateLAN');
	resetVoidRedInput('dateLAN');
}

function resetDescriptionLAN(){
	resetVoidRedInput('descriptionLAN');
	resetError('erreurDescriptionLAN');
}

function resetVilleLAN(){
	resetVoidRedInput('villeLAN');
	resetError('erreurVilleLAN');
}

function resetAdresseLAN(){
	resetVoidRedInput('adresseLAN');
	resetError('erreurAdresseLAN');
}
