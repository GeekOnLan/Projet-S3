$(function() {
    $(".sB").click(function() {
        deleteNotif($(this).parent().attr("id"));
    });
});

//Vide la div contenant la notification
var deleteDivNotif = function(id){
    $("#"+id).remove();
    $("#notifNumber").html($("#notifNumber").html()-1);
    if($("#notifNumber").html()<=0)
        $("#notifNumber").remove();
    if($(".sB")[0]==null)
        $("#notification").css('display','block');
}
var deleteNotif = function(id){
    $.ajax({
        url: 'scriptPHP/deleteNotif.php',
        type: 'GET',
        data: "id=" + id,
        success: function (res, statut) {
            deleteDivNotif(id)
        },
        error: function (res, statut, error) {
            console.log(error);
        }
    })
};