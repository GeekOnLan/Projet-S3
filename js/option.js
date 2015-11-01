var affiche = function(even){
    if(even.clientX>100){
        document.getElementById('option').style.display="none";
    }
    else{
        document.getElementById('option').style.display="block";
    }
}

Window.onload=affiche;
document.addEventListener('mousemove',affiche);

