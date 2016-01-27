var xhrLan;
if (window.XMLHttpRequest) {
	xhrLan = new XMLHttpRequest();
} else {
	// code for IE6, IE5
	xhrLan = new ActiveXObject("Microsoft.XMLHTTP");
}
function verifyUpdate(){
	//si tout est bon on envoit
	var erreur = getAllErrorTournoi();
	if(erreur=="     "){
		document.modifTournoi.submit();
	}
}

function verifyNameLANUpdate() {
	xhrLan.abort();
	var nameLAN = document.getElementsByName('nameLAN')[0].value;
	var original = document.getElementsByName('originalName')[0].value;
	console.log(original);
	if(nameLAN!=""){
		if(nameLAN==original){
			setError('erreurNameLAN',' ');
		}
		else{
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
					xhrLan.open('GET', 'scriptPHP/TournoiValide.php?LANName=' + nameLAN);
					xhrLan.send(null);
				}
			}else{
				voidRedInput('nameLAN');
				setError('erreurNameLAN', 'nom de Tournoi trop petit');
			}
		}
	}
}