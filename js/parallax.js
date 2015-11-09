/**
 * Created by PandarkMeow on 04/11/2015.
 */
$(document).ready(function() {
    alert($('#parallax').length);
       $('#parallax').mousemove(
        function(e){
            //alert("coucou");
            /*Position souris*/
            var offset = $(this).offset();
            var posX = e.pageX - offset.left;
            var posY = e.pageY - offset.top;

            /*Position en pourcentage*/
            var mouseX = Math.round(posX / $(this).width() * 100);
            var mouseY = Math.round(posY / $(this).height() * 100);

            /*Position de chaques couches*/
            $(this).children('img').each(
                function(){
                    //alert("coucou");
                    var myX =  ($('#parallax').width() - $(this).width()) * (mouseX / 100);
                    var myY =  ($('#parallax').height() - $(this).height()) * (mouseY / 100);

                    var cssObj = {
                        'left' : myX + 'px',
                        'top' : myY + 'px'
                    };
                    $(this).animate({left: myX, top: myY}, {duration: 50, queue: false, easing : 'linear'});
                }
            );
        }
    );
});
