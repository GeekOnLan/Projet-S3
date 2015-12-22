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
    $("#"+id).empty();
}

var deleteInvit = function(id,choix){
    $.ajax({
        url: 'scriptPHP/deleteInvit.php',
        type: 'GET',
        data: { id: id, choix : choix},
        success: function (res, statut) {
                deleteDiv(id)
        },
        error: function (res, statut, error) {
            console.log(error);
        }
    })
};
