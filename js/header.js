$(document).ready(function() {
    var nav = $("#menu a");

    for(var i = 0; i < nav.length; i++) {
        nav[i].title = nav[i].innerHTML;
    }
});