$(function() {
    $(".details").click(function() {
      var idTournoi= $(this).parent().parent().attr("id")
      var idLan= $(this).parent().parent().parent().parent().attr("id")
      showDetails(idLan,idTournoi);
    });
});


var showDetails = function(idLan,idTournoi){
    $.ajax({
        url: 'scriptPHP/descriptionTournoi.php',
        type: 'GET',
        data: "idLan=" + idLan,
        success: function (res, statut) {
          var desc = document.getElementById("description");
          desc.style.display="block";
          desc.innerHTML += "<p>"+res+"</p><button onclick=\"fermer()\">fermer</button>";

        },
        error: function (res, statut, error) {
            console.log(error);
        }
    })
};


function fermer(){
  var desc = document.getElementById("description");
  desc.innerHTML="<h1>Description du Tournoi</h1>";
  desc.style.display="none";
}
