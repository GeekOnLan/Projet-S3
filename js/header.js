window.onload = function() {
    var nav = document.getElementById("menu").getElementsByTagName("a");

    for(var i = 0; i < nav.length; i++) {
        nav[i].title = nav[i].innerHTML;
    }
};