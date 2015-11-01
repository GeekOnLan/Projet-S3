var vue = false

function afficher(){
    if(vue) {
        document.getElementById('optionmenu').style.display = "none";
        document.getElementById('optionimage').style.display = "block";
        vue = !vue;
    }
    else{
        document.getElementById('optionmenu').style.display = "block";
        document.getElementById('optionimage').style.display = "none";
        vue = !vue;
    }
}

document.onclick = function(){
    afficher();
}