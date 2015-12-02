function ajax(idLan){
	$.ajax({
		url		: 'getLan.php',
		type	: 'GET',
		data	: 'idLan=' + idLan,
		dataType: "json",
		success	: function(data) {
			console.log('recut');
			$("#results").html('');
			//$('#result').html(data);
			alert(data);
		},
		error : function(statut, erreur) {
			alert('Error ' + statut + ' : ' + erreur);
		}
});
};
