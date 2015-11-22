window.addEventListener("keypress",function(even){
	if(even.keyCode === 13)
		verifyInscription();
});

function verifyLAN(){
	//si tout est bon on envoit
	var name = verifyNameLAN();
	var date = verifyDateLAN();
	var ville = verifyVilleLAN();
	var adresse = verifyAdresseLAN();
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

function resetNameLAN(){
	resetError('erreurNameLAN');
	resetInput('nameLAN');
}

function resetDateLAN(){
	resetError('erreurDateLAN');
	resetInput('dateLAN');
}

function resetDescriptionLAN(){
	resetError('erreurDescriptionLAN');
	resetInput('descriptionLAN');
}

function resetVilleLAN(){
	resetError('erreurVilleLAN');
	resetInput('villeLAN');
}

function resetAdresseLAN(){
	resetError('erreurAdresseLAN');
	resetInput('adresseLAN');
}

//----------------------------------------------------------//
//nom
//----------------------------------------------------------//

function verifyNameLANForm(){
	var name = document.getElementsByName('nameLAN')[0].value;
	if(name != '' && !verifyName(name)){
		setInput('nameLAN');
		setError('erreurNameLAN','Nom incorect');
	}
	else
		resetNameLAN();
}

function verifyNameLAN(){
	var name = document.getElementsByName('nameLAN')[0].value;
	if(name == '') {
		setInput('nameLAN');
		setError('erreurNameLAN', 'Champ obligatoire');
		return false;
	}
	else if(!verifyName(name)){
		setInput('nameLAN');
		setError('erreurNameLAN','Nom incorrect');
		return false;
	}
	else {
		resetNameLAN();
		return true;
	}
}

//test le nom de lan
function verifyName(name){
	var re = /^[a-zA-Z]{1,20}$/;
	return re.test(name);
}

//----------------------------------------------------------//
//date
//----------------------------------------------------------//


//verify la date dans le formulaire
function verifyDateLANForm(){
	var d = document.getElementsByName('dateLAN')[0].value;
	if(d != '' && !birthdayTest(d)){
		setInput('dateLAN');
		setError('erreurDateLAN','date au format : JJ/MM/AAAA');
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
			setInput('dateLAN');
			setError('erreurDateLAN','cette date n\'existe pas');
		}
		else
			resetDateLAN();
	}
}

function verifyDateLAN(){
	var d = document.getElementsByName('dateLAN')[0].value;
	if(d=='') {
		setInput('dateLAN');
		setError('erreurDateLAN','champ obligatoire');
		return false;
	}
	if(d != '' && !birthdayTest(d)){
		setInput('dateLAN');
		setError('erreurDateLAN','date de naissance au format : JJ/MM/AAAA');
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
			setInput('dateLAN');
			setError('erreurDateLAN','cette date n\'existe pas');
			return false;
		}
		else {
			resetDateLAN();
			return true;
		}
	}
}

//teste une date
function birthdayTest(dateBirth){
	var re = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
	return re.test(dateBirth);
}

//----------------------------------------------------------//
//nom
//----------------------------------------------------------//

function verifyVilleLANForm(){
	var ville = document.getElementsByName('villeLAN')[0].value;
	if(ville != '' && !verifyName(ville)){
		setInput('villeLAN');
		setError('erreurVilleLAN','Ville incorect');
	}
	else
		resetVilleLAN();
}

function verifyVilleLAN(){
	var ville = document.getElementsByName('nameLAN')[0].value;
	if(ville == '') {
		setInput('villeLAN');
		setError('erreurVilleLAN', 'Champ obligatoire');
		return false;
	}
	else if(!verifyName(ville)){
		setInput('villeLAN');
		setError('erreurVilleLAN','Ville incorrect');
		return false;
	}
	else {
		resetVilleLAN();
		return true;
	}
}

//----------------------------------------------------------//
//adresse
//----------------------------------------------------------//

function verifyAdresseLANForm(){
	var ville = document.getElementsByName('adresseLAN')[0].value;
}

function verifyAdresseLAN(){
	var ville = document.getElementsByName('adresseLAN')[0].value;
	if(ville == '') {
		setInput('adresseLAN');
		setError('erreurAdresseLAN', 'Champ obligatoire');
		return false;
	}
	else {
		resetVilleLAN();
		return true;
	}
}
