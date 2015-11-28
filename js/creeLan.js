var xhrVille;
if (window.XMLHttpRequest) {
	xhrVille = new XMLHttpRequest();
} else {
	// code for IE6, IE5
	xhrVille = new ActiveXObject("Microsoft.XMLHTTP");
}

var xhrLan;
if (window.XMLHttpRequest) {
	xhrLan = new XMLHttpRequest();
} else {
	// code for IE6, IE5
	xhrLan = new ActiveXObject("Microsoft.XMLHTTP");
}

window.addEventListener("keypress",function(even){
	if(even.keyCode === 13)
		verifyLAN();
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
		var nameLAN = document.getElementsByName('nameLAN')[0].value;
		var dateLAN = document.getElementsByName('dateLAN')[0].value;
		var descriptionLAN =  document.getElementsByName('descriptionLAN')[0].value;
		var ville = document.getElementsByName('villeLAN')[0].value;
		var adresseLAN = document.getElementsByName('adresseLAN')[0].value;
		document.ajoutLAN.submit();
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
//ville
//----------------------------------------------------------//

function verifyVilleLAN(){
	console.log("ville apeler");
	xhrVille.abort();
	var ville = document.getElementsByName('villeLAN')[0].value;
	if(ville!=""){
		var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ\- \_]{0,30}/);
		match = regex.exec(ville)==ville;
		if(!match){
			voidRedInput('villeLAN');
			setError('erreurVilleLAN', 'caractères non autorisé utilisé');
		}else{
			document.getElementById("ville").setAttribute("src","resources/gif/load.gif");
			xhrVille.addEventListener('readystatechange', function () {
				if (xhrVille.readyState === 4 && xhrVille.status === 200) {
					document.getElementById("ville").setAttribute("src","resources/img/Ville.png");
					var xml = xhrVille.responseXML.getElementsByTagName('response').item(0).textContent;
					if (xml == "false") {
						voidRedInput('villeLAN');
						setError('erreurVilleLAN', "cette ville n'existe pas");
					}
					else {
						setError('erreurVilleLAN',' ');
					}
				}
			}, true);
			xhrVille.open('GET', 'scriptPHP/VilleValide.php?Ville=' + ville);
			xhrVille.send(null);
		}
	}
}

//----------------------------------------------------------//
//nameLAN
//----------------------------------------------------------//


//verifier le pseudo avec ajax pour le formulaire
function verifyNameLAN() {
	console.log("Lan apeler");
	xhrLan.abort();
	var nameLAN = document.getElementsByName('nameLAN')[0].value;
	if(nameLAN!=""){
		var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ\- \_]{0,30}/);
		match = regex.exec(nameLAN)==nameLAN;
		if(!match){
			voidRedInput('nameLAN');
			setError('erreurNameLAN', 'caractères non autorisé utilisé');
		}else if (nameLAN.length>2) {
			if(nameLAN.length>31){
				voidRedInput('nameLAN');
				setError('erreurNameLAN', 'nom de LAN trop grand ');
			}else{
				document.getElementById("lanName").setAttribute("src","resources/gif/load.gif");
				xhrLan.addEventListener('readystatechange', function () {
					if (xhrLan.readyState === 4 && xhrLan.status === 200) {
						document.getElementById("lanName").setAttribute("src","resources/img/Lan.png");
						var xml = xhrLan.responseXML.getElementsByTagName('response').item(0).textContent;
						if (xml == "false") {
							voidRedInput('nameLAN');
							setError('erreurNameLAN', 'ce nom de LAN est déjà pris');
						}
						else {
							setError('erreurNameLAN',' ');
						}
					}
				}, true);
				xhrLan.open('GET', 'scriptPHP/LANValide.php?LANName=' + nameLAN);
				xhrLan.send(null);
			}
		}else{
			voidRedInput('nameLAN');
			setError('erreurNameLAN', 'nom de LAN trop petit');
		}
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
		if(j!=""&&m!=""&&a!=""){
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
				}
			}
		}else{
			voidRedInput('dateLAN');
			setError('erreurDateLAN','veillez entrer une date sous la forme jj/mm/yyyy');
		}
	}
	/*

	*/
}


//----------------------------------------------------------//
//description
//----------------------------------------------------------//

//verifier le mail pour le formulaire
function verifyDescriptionLAN(){
	var description = document.getElementsByName('descriptionLAN')[0].value;
	if(description!=""){
		var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ\- \_]{0,30}/);
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
		}
	}else{
		setError('erreurDescriptionLAN',' ');
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
	if(adresse!=""){
		var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ\- \_]{0,30}/);
		match = regex.exec(adresse)==adresse;
		if(!match){
			voidRedInput('adresseLAN');
			setError('erreurAdresseLAN', 'caractères non autorisé utilisé');
		}else if(valideAdresse(adresse)){
			setError('erreurAdresseLAN',' ');
		}
		else{
			voidRedInput('adresseLAN');
			setError('erreurAdresseLAN','Adresse incorrect');
		}
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
