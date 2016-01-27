$(function() {
    $(".yB").click(function() {
        deleteInvit($(this).parent().attr("id"),true);
    });
    $(".nB").click(function() {
        deleteInvit($(this).parent().attr("id"),false);
    });
});

//Vide la div contenant l'invitation
var deleteDiv = function(id){
    $("#"+id).remove();
    $("#notifNumber").html($("#notifNumber").html()-1);
    if($("#notifNumber").html()<=0)
        $("#notifNumber").remove();
    if($(".yB")[0]==null)
        $("#invitation").css('display','block');
}

var deleteInvit = function(id,choix){
    $.ajax({
        url: 'scriptPHP/deleteInvit.php',
        type: 'GET',
        data: { id: id, choix : choix},
        success: function (res, statut) {
                console.log(res);
                deleteDiv(id)
        },
        error: function (res, statut, error) {
            console.log(error);
        }
    })
};
