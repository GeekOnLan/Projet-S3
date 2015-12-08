$(function() {
	$("#mainframe form[name='filter'] > button").each(function(index) {
		$(this).on("click", function() {
			expandFilter(index);
		});
	});

	$("#searchSubmit").click(sendSearch);
});

/**
 * Remplit le tableau des résultat avec
 * les Lans trouvée
 *
 * @param res - Le résultat en JSON
 */
var show = function(res) {
	var resTable = $("#searchRes");
    resTable.empty();

	for(var i in res) {
        resTable.append("<tr><td>" + res[i].name + "</td></tr>");
	}
};

/**
 * Retourne la requête GET sous forme d'une chaine
 * de caractères
 *
 * @returns {string} la requête GET
 */
var getFilters = function() {
    var filters = [
        $("#mainframe form[name='filter'] input[name='name']"),
        $("#mainframe form[name='filter'] input[name='departement']"),
        $("#mainframe form[name='filter'] input[name='ville']"),
        $("#mainframe form[name='filter'] input[name='gratuit']"),
        $("#mainframe form[name='filter'] input[name='equipe']"),
        $("#mainframe form[name='filter'] input[name='solo']")
    ];

    // On parcours tout les champs pour récuperer ceux remplit
    var res = [];
    for(var i in filters) {
        if(filters[i].val() != "" && filters[i].attr("type") != "checkbox")
            res.push(filters[i].attr("name") + "=" + filters[i].val());
        else if(filters[i].attr("type") == "checkbox" && filters[i].is(":checked"))
            res.push(filters[i].attr("name"));
    }

    return res.join("&");
};

/**
 * Envois dans la requête en AJAJ
 */
var sendSearch = function() {
	$.ajax({
		url: 'scriptPHP/searchWithFilter.php',
		type: 'GET',
		data: getFilters(),
		dataType: 'json',
		success : function(res, statut) {
			show(res);
		},
		error : function(res, statut, error) {
			console.log(error);
		}
	})
};

/**
 * Etend le groupe de filtre
 *
 * @param filterButton - le numéro du bouton
 */
var expandFilter = function(filterButton) {
	var expandableDiv = $("#mainframe form[name='filter'] > div")[filterButton];
	expandableDiv = $(expandableDiv);

	if(expandableDiv.hasClass("open")) {
		expandableDiv.css({
			transform: "scaleY(1)",
		});
	} else {
		expandableDiv.css({
			transform: "scaleY(0)",
		});
	}

	expandableDiv.toggleClass("open");
}
