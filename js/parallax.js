/**
 * Created by PandarkMeow on 04/11/2015.
 */
$(function() {
    var bottom = $("#parallax img[alt='bottom']");
    var middle = $("#parallax img[alt='middle']");
    var front = $("#parallax img[alt='front']");

    /*alert(bottom.attr('alt'));*/
    $("body").mousemove(function(e) {
        /*mouvement de la couche bottom*/
        var mouseXBot = (e.pageX - ($(window).width() / 2)) * 0.01;
        var mouseYBot = (e.pageY - ($(window).height() / 2)) * 0.01;

        bottom.css('transform', 'translate3d(' + mouseXBot + 'px,' + mouseYBot + 'px, 0px)');

        /*mouvement de la couche middle*/
        var mouseXMid = (e.pageX - ($(window).width() / 2)) * 0.015;
        var mouseYMid = (e.pageY - ($(window).height() / 2)) * 0.015;

        middle.css('transform', 'translate3d(' + mouseXMid + 'px,' + mouseYMid + 'px, 0px)');

        /*mouvement de la couche front*/
        var mouseXTop = (e.pageX - ($(window).width() / 2)) * 0.02;
        var mouseYTop = (e.pageY - ($(window).height() / 2)) * 0.02;

        front.css('transform', 'translate3d(' + mouseXTop + 'px,' + mouseYTop + 'px, 0px)');
    });
});