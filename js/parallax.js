/**
 * Created by PandarkMeow on 04/11/2015.
 */
/*var stopParallax = false;

$(function() {
    var bottom = $("#parallax .bottom");
    var middle = $("#parallax .middle");
    var front = $("#parallax .front");

    $("body").mousemove(function(e) {
        if(stopParallax) return;

        //mouvement de la couche bottom
       var mouseXBot = (e.pageX - ($(window).width() / 2)) * 0.01;
        var mouseYBot = (e.pageY - ($(window).height() / 2)) * 0.01;

        bottom.css('transform', 'translate3d(' + mouseXBot + 'px,' + mouseYBot + 'px, 0px)');

        //mouvement de la couche middle
        var mouseXMid = (e.pageX - ($(window).width() / 2)) * 0.03;
        var mouseYMid = (e.pageY - ($(window).height() / 2)) * 0.03;

        middle.css('transform', 'translate3d(' + mouseXMid + 'px,' + mouseYMid + 'px, 0px)');

        //mouvement de la couche front
        var mouseXTop = (e.pageX - ($(window).width() / 2)) * 0.09;
        var mouseYTop = (e.pageY - ($(window).height() / 2)) * 0.09;

        front.css('transform', 'translate3d(' + mouseXTop + 'px,' + mouseYTop + 'px, 0px)');
    });
});*/

$(function() {
	var parallax = new Parallax(7);
	$("body").mousemove(function(e) {
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