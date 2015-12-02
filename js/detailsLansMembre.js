xhr = null;
function ajax(idLan){
	$.ajax({
		url		: 'getLan.php',
		type	: 'GET',
		data	: 'id=' + idLan,
		dataType: "json",
		success	: function(data) {
			$("#results").html('');
			//$('#result').html(data);
			alert(data);
		},
		error : function(statut, erreur) {
			alert('Error ' + statut + ' : ' + erreur);
		}
});
};
