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
	if(d!=''){
		var j=(d.substring(0,2));
		var m=(d.substring(3,5))-1;
		var a=(d.substring(6));
		m+="";
		var d=new Date(a,m,j);

		var b = document.getElementsByName('dateLAN')[0].value;
		if(b!=''){
			var j=(b.substring(0,2));
			var m=(b.substring(3,5))-1;
			var a=(b.substring(6));
			m+="";
			var b=new Date(a,m,j);
		}else{
			var b=new Date();
		}
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
		var regex =new RegExp(/[a-zA-Z0-9'àâéèêôùûçïÀÂÉÈÔÙÛÇ\-\: \_]{0,30}/);
		match = regex.exec(description)==description;
		if(!match){
			document.getElementsByName('descriptionTournoi')[0].value="Tournoi créer par";
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
					document.getElementById("tournoiJeuName").setAttribute("src","resources/img/Jeu.png");
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
			xhrNameJeu.open('GET', 'scriptPHP/JeuValide.php?NameJeu=' + jeu);
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
