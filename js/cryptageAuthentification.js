function sha256(){
    var pass = $("#connexionForm input[name='pass']").val();
    var login = $("#connexionForm input[name='login']").val();
    if(pass=="")
        setInput('pass');
    if(login=="")
        setInput('login');
    else if(pass!=""){
        cryptpass = CryptoJS.SHA256(pass);
        $("#connexionForm input[name='pass'],#connexionForm input[name='login']").val('');
        temp = (CryptoJS.SHA1(CryptoJS.SHA1(login) + $("#connexionForm input[name='hiddenCrypt']").val() + cryptpass));
        $("#connexionForm input[name='hiddenCrypt']").val(temp);
        document.connexion.submit();
    }
}

window.addEventListener("keypress",function(even){
    if(even.keyCode === 13)
        sha256();
});

function resetInput(name){
    document.getElementsByName(name)[0].setCustomValidity("");
}

function setInput(name){
    document.getElementsByName(name)[0].setCustomValidity("Champ invalide")
}