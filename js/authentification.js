function sha256(){
    var pass = $("#connexionForm input[name='pass']").val();
    var login = $("#connexionForm input[name='login']").val();
    if(pass=="")
        setInputAuth('pass');
    if(login=="")
        setInputAuth('login');
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

function resetInputAuth(name){
    document.getElementsByName(name)[0].setCustomValidity("");
    console.log('apel');
}

function setInputAuth(name){
    document.getElementsByName(name)[0].setCustomValidity("Champ invalide")
}