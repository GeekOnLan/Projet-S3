$(document).ready(function() {
    $("#options button").click(function () {
    	$("#options button").addClass('hiddenButton').delay(200).queue(function() {
    		$("#options ul").addClass('activeNav');
    	});
    });
});