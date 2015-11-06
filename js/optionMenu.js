$(document).ready(function() {
    $("#options button").click(function () {
    	$("#options button").addClass('hiddenButton').delay(200).queue(function() {
			$("#options ul li").each(function(i) {
				var self = $(this);
				setTimeout(function(){
					self.addClass('activeNav');
				}, 200 * i);
			});
    	});
    });
});