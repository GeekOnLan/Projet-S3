function ajax(idLan){
	$.ajax({
		url		: 'getLan.php',
		type	: 'GET',
		data	: 'idLan=' + idLan,
		dataType: "html",
		success	: function(data) {
			console.log('recut');
			$("#results").html('');
			$(data).appendTo('#results');
		},
		error : function(statut, erreur) {
			alert('Error ' + statut + ' : ' + erreur);
		}
});
};
