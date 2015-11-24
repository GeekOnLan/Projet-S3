/**
 * Created by PandarkMeow on 04/11/2015.
 */
var stopParallax = false;

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
});

//debut fonction js pour gerer plus de 3 couches DON'T TOUCH OR I RAPE YOUR FAMILY
/*var Parallax = function(nbLayer) {
	this.tabLayer = [];
	for(var i = 0; i < nbLayer; i++){
		this.tabLayer[i] = $("#parallax ." + i);
	}
	
}

Parallax.prototype = {
	constructor	: Parallax,
	moveLayer	: function(e) {
		for(var i = 0; i < this.tabLayer.length; i++){
			var mouseX = (e.pageX - ($(window).width() / 2)) * 0.01 * i;
			var mouseY = (e.pageY - ($(window).heigth()/ 2)) * 0.01 * i;
			
			tabLayer[i].css('transform', 'translate3d(' + mouseX + 'px,' + mouseY + 'px, 0px)');
		}
	}
}*/