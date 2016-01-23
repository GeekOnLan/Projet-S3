var stopParallax = false;

$(function() {
	var parallax = new Parallax(7);
	$("body").mousemove(function(e) {
		if(!stopParallax)
			parallax.moveLayer(e);
	});
});

//debut fonction js pour gerer plus de 3 couches DON'T TOUCH OR I RAPE YOUR FAMILY
var Parallax = function(nbLayer) {
	var bigDiv = $("#parallax");
	this.tabLayer = [];
	for(var i = 0; i < nbLayer; i++){
		bigDiv.append("<div class='" + i + "'></div>");
		this.tabLayer[i] = $("#parallax ." + i);
		this.tabLayer[i].css('background-image', "url('resources/parallax/" + i + ".png')");
		this.tabLayer[i].css('margin-left', "-30px");
		this.tabLayer[i].css('padding', "30px 30px 30px 30px");
	}
	
}

Parallax.prototype = {
	constructor	: Parallax,
	moveLayer	: function(e) {
		for(var i = 1; i < this.tabLayer.length; i++){
			var mouseX = (e.pageX - ($(window).width() / 2)) * 0.025 * Math.abs(i-this.tabLayer.length);
			var mouseY = (e.pageY - ($(window).height()/ 2)) * 0.025 * Math.abs(i-this.tabLayer.length);
			this.tabLayer[i].css('transform', 'translate3d(' + mouseX + 'px,' + mouseY + 'px, 0px)');
		}
	}
}