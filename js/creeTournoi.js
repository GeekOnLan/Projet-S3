
window.addEventListener("keypress",function(even){
	if(even.keyCode === 13)
		verifyLAN();
});


var xhrNameJeu;
if (window.XMLHttpRequest) {
	xhrNameJeu = new XMLHttpRequest();
} else {
	// code for IE6, IE5
	xhrNameJeu = new ActiveXObject("Microsoft.XMLHTTP");
}

var xhrDateTournoi;
if (window.XMLHttpRequest) {
	xhrDateTournoi = new XMLHttpRequest();
} else {
	// code for IE6, IE5
	xhrDateTournoi = new ActiveXObject("Microsoft.XMLHTTP");
}

function getAllErrorTournoi(){
	allNeadNullTournoi();
	error=document.getElementById("erreurNameTournoi").innerHTML;
	error+=document.getElementById("erreurNameJeuTournoi").innerHTML;
	error+=document.getElementById("erreurDateTournoi").innerHTML;
	error+=document.getElementById("erreurHeureTournoi").innerHTML;
	error+=document.getElementById("erreurNbEquipeMax").innerHTML;
	error+=document.getElementById("erreurNbMembreMax").innerHTML;
	error+=document.getElementById("erreurDescriptionTournoi").innerHTML;
	//"       "
	console.log(error.length);
	return error;
}

function verifyTournoi(){
	//si tout est bon on envoit
	var erreur = getAllErrorTournoi();
	if(erreur=="       "){

		var nameTournoi = document.getElementsByName('nameTournoi')[0].value;
		var nameJeuTournoi = document.getElementsByName('nameJeuTournoi')[0].value;
		var dateTournoi =  document.getElementsByName('dateTournoi')[0].value;
		var heureTournoi = document.getElementsByName('heureTournoi')[0].value;
		var nbEquipeMax = document.getElementsByName('nbEquipeMax')[0].value;
		var nbMembreMax = document.getElementsByName('nbMembreMax')[0].value;

		document.ajoutTournoi.submit();
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

function allNeadNullTournoi(){

	voidRedInput('nameTournoi');
	setError('erreurNameTournoi',"ce champ ne peut etre vide");
	voidRedInput('nameJeuTournoi');
	setError('erreurNameJeuTournoi',"ce champ ne peut etre vide");
	voidRedInput('dateTournoi');
	setError('erreurDateTournoi', 'ce champ ne peut etre vide');
	voidRedInput('heureTournoi');
	setError('erreurHeureTournoi',"ce champ ne peut etre vide");
	voidRedInput('nbEquipeMax');
	setError('erreurNbEquipeMax',"ce champ ne peut etre vide");
	voidRedInput('nbMembreMax');
	setError('erreurNbMembreMax',"ce champ ne peut etre vide");
}


function verifyNameTournoi(){
	var nameTournoi = document.getElementsByName('nameTournoi')[0].value;
	if(nameTournoi!=""){
		var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ\- \_]{0,30}/);
		match = regex.exec(nameTournoi)==nameTournoi;
		if(!match){
			voidRedInput('nameTournoi');
			setError('erreurNameTournoi', 'caractères non autorisé utilisé');
		}else if (nameTournoi.length>2) {
			if(nameTournoi.length>31){
				voidRedInput('nameTournoi');
				setError('erreurNameTournoi', 'nom de Tournoi trop grand ');
			}else{
				setError('erreurNameTournoi',' ');
			}
		}
	}
}

function verifyDateTournoi(){
	var d = document.getElementsByName('dateTournoi')[0].value;
	var b = document.getElementsByName('dateLAN')[0].innerHTML;
	if(b==""){
		b = document.getElementsByName('dateLAN')[0].value;
	}
	console.log(b);
		if(b!=''){
			var j=(b.substring(0,2));
			var m=(b.substring(3,5))-1;
			var a=(b.substring(6));
			m+="";
			var b=new Date(a,m,j);
		}else{
			var b=new Date();
		}
	if(d!=''){
		var j=(d.substring(0,2));
		var m=(d.substring(3,5))-1;
		var a=(d.substring(6));
		m+="";
		var d=new Date(a,m,j);

		if(j!=""&&m!=""&&a!=""){
			if(d<b){
				voidRedInput('dateTournoi');
				setError('erreurDateTournoi','vous ne pouvez organiser de tournoi avant votre LAN');
			}else{
				var rep= (d.getFullYear()!=a || d.getMonth()!=m) ? "date invalide" : "date valide";

				if (rep=="date invalide"){
					voidRedInput('dateTournoi');
					setError('erreurDateTournoi','cette date n\'existe pas');
				}else{
					setError('erreurDateTournoi',' ');
				}
			}
		}else{
			voidRedInput('dateTournoi');
			setError('erreurDateTournoi','veillez entrer une date sous la forme jj/mm/yyyy');
		}
	}
}

function verifyDescriptionTournoi(){
	var description = document.getElementsByName('descriptionTournoi')[0].value;
	if(description!=""){
		var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ\-\+\.\!\?\,\(\)\'\"\: \_]{0,30}/);
		match = regex.exec(description)==description;
		if(!match){
			document.getElementsByName('descriptionTournoi')[0].value="Tournoi créée par";
			setError('erreurDescriptionTournoi', 'caractères non autorisé utilisé');
		}else if(description.length>255){
			voidRedInput('descriptionTournoi');
			setError('erreurDescriptionTournoi', 'description trop longue');
		}
		else{
			setError('erreurDescriptionTournoi',' ');
		}
	}else{
		setError('erreurDescriptionTournoi',' ');
	}
}

function verifyHeureTournoi(){
	var d = document.getElementsByName('heureTournoi')[0].value;
	if(d!=''){
		var h=(d.substring(0,2));
		var m=(d.substring(3,5));
		if(h!=""&&m!=""){
			if(((23-h)>=0&&(23-h)<=23)&&((59-m)>=0&&(59-m)<=59)){
				setError('erreurHeureTournoi',' ');
			}else{
				voidRedInput('heureTournoi');
				setError('erreurHeureTournoi','cette heure n\'existe pas');
			}
		}else{
			voidRedInput('heureTournoi');
			setError('erreurHeureTournoi','veillez entrer une heure sous la forme HH:MM');
		}
	}
}
function verifyNbEquipeMax(){
	var nb = document.getElementsByName('nbEquipeMax')[0].value;
	if(nb!=""){
		if(nb>0&&nb<10000){
			setError('erreurNbEquipeMax',' ');
		}else{
			voidRedInput('nbEquipeMax');
			setError('erreurNbEquipeMax',"le nombre d'equipe est incorrecte");
		}
	}

}
function verifyNbMembreMax(){
	console.log("verifNbMembreMax");
	var nb = document.getElementsByName('nbMembreMax')[0].value;
	if(nb!=""){
		console.log(nb);
		if(nb>0&&nb<10000){
			setError('erreurNbMembreMax',' ');
		}else{
			voidRedInput('nbMembreMax');
			setError('erreurNbMembreMax',"le nombre de membres par équipe est incorrecte");
		}
	}
}

function verifyNameJeuTournoi(){
	console.log("verifNameJeuTournoi");
	xhrNameJeu.abort();
	var jeu = document.getElementsByName('nameJeuTournoi')[0].value;
	if(jeu!=""){
		var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ\- \_]{0,30}/);
		match = regex.exec(jeu)==jeu;
		if(!match){
			voidRedInput('nameJeuTournoi');
			setError('erreurNameJeuTournoi', 'caractères non autorisé utilisé');
		}else{
			document.getElementById("tournoiJeuName").setAttribute("src","resources/gif/load.gif");
			xhrNameJeu.addEventListener('readystatechange', function () {
				if (xhrNameJeu.readyState === 4 && xhrNameJeu.status === 200) {
					document.getElementById("tournoiJeuName").setAttribute("src","resources/img/Lan.png");
					var xml = xhrNameJeu.responseXML.getElementsByTagName('response').item(0).textContent;
					if (xml == "false") {
						voidRedInput('nameJeuTournoi');
						setError('erreurNameJeuTournoi', "ce jeu n'existe pas");
					}
					else {
						setError('erreurNameJeuTournoi',' ');
					}
				}
			}, true);
			xhrNameJeu.open('GET', 'scriptPHP/jeuValide.php?NameJeu=' + jeu);
			xhrNameJeu.send(null);
		}
	}
}

function resetNameTournoi(){
	resetError('erreurNameTournoi');resetVoidRedInput('nameTournoi');
}

function resetNameJeuTournoi(){
	resetError('erreurNameJeuTournoi');resetVoidRedInput('nameJeuTournoi');
}

function resetDateTournoi(){
	resetError('erreurDateTournoi');resetVoidRedInput('dateTournoi');
}

function resetHeureTournoi(){
	resetVoidRedInput('heureTournoi');resetError('erreurHeureTournoi');
}

function resetDescriptionTournoi(){
	resetVoidRedInput('descriptionTournoi');resetError('erreurDescriptionTournoi');
}

function resetNbEquipeMax(){
	resetVoidRedInput('nbEquipeMax');resetError('erreurNbEquipeMax');
}

function resetNbMembreMax(){
	resetVoidRedInput('nbMembreMax');resetError('erreurNbMembreMax');
}

function allNeadNullLAN(){
	if (document.getElementById("erreurNameTournoi").innerHTML == ""){
		voidRedInput('nameTournoi');
		setError('erreurNameTournoi',"ce champ ne peut etre vide");
	}
	if (document.getElementById("erreurNameJeuTournoi").innerHTML == ""){
		voidRedInput('nameJeuTournoi');
		setError('erreurNameJeuTournoi',"ce champ ne peut etre vide");
	}
	if (document.getElementById("erreurDateTournoi").innerHTML == ""){
		voidRedInput('dateTournoi');
		setError('erreurDateTournoi', 'ce champ ne peut etre vide');
	}
	if (document.getElementById("erreurHeureTournoi").innerHTML == ""){
		voidRedInput('heureTournoi');
		setError('erreurHeureTournoi',"ce champ ne peut etre vide");
	}
	if (document.getElementById("erreurNbEquipeMax").innerHTML == ""){
		voidRedInput('nbEquipeMax');
		setError('erreurNbEquipeMax',"ce champ ne peut etre vide");
	}
	if (document.getElementById("erreurNbMembreMax").innerHTML == ""){
		voidRedInput('nbMembreMax');
		setError('erreurNbMembreMax',"ce champ ne peut etre vide");
	}
}
