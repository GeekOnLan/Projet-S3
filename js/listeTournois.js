function showDetails(idTournoi, idLan){
  window.alert(idTournoi);
  window.alert(idLan);

  r = new Request({
        url:"scriptPHP/descriptionTournoi.php?idTournoi="+idTournoi+"&idLan="+idLan,
        handleAs:'json',
        asynchronous:true,
        onSuccess:function(result){
          console.log(result);
          document.getElementById("description").innerHTML+="<p>result</p>";
        },
        onError:function(){window.alert('erreur');}
  });
}
