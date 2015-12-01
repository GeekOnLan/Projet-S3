$(function() {
	$("#mainframe form[name='filter'] > button").each(function(index) {
		$(this).on("click", function() {
			expandFilter(index);
		});
	});
});

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
