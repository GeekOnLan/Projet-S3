
function verifyUpdate(){
	//si tout est bon on envoit
	var erreur = getAllErrorTournoi();
	if(erreur=="       "){
		document.modifTournoi.submit();
	}
}
