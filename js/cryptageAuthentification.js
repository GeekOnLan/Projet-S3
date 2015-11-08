/*function sha256(){
    pass = document.getElementsByName('pass')[0].value;
    log = document.getElementsByName('login')[0].value;
    if(pass=="")
        setInput('pass');
    if(login=="")
        setInput('login');
    else if(pass!=""){
        cryptpass = CryptoJS.SHA256(pass);
        document.getElementsByName('pass')[0].value = "";
        document.getElementsByName('login')[0].value = "";
        temp = CryptoJS.SHA1(CryptoJS.SHA1(log) + document.getElementsByName('hiddenCrypt')[0].value + cryptpass);
        document.getElementsByName('hiddenCrypt')[0].value = temp;
        document.connexion.submit();
    }
}

window.addEventListener("keypress",function(even){
    if(even.keyCode === 13)
        sha256();
})

function resetInput(name){
    document.getElementsByName(name)[0].style.borderColor = '';
}

function setInput(name){
    document.getElementsByName(name)[0].style.borderColor = 'red';
}*/

function sha256(){
    pass = $("#connexionForm input[name='pass']").val();
    login = $("#connexionForm input[name='login']").val();
    if(pass=="")
        setInput('pass');
    if(login=="")
        setInput('login');
    else if(pass!=""){
        cryptpass = CryptoJS.SHA256(pass);
        $("#connexionForm input[name='pass'],#connexionForm input[name='login']").val('');
        temp = (CryptoJS.SHA1(CryptoJS.SHA1(log) + $("#connexionForm input[name='hiddenCrypt']").val() + cryptpass));
        $("#connexionForm input[name='hiddenCrypt']").val(temp);
        document.connexion.submit();
    }
}

window.addEventListener("keypress",function(even){
    if(even.keyCode === 13)
        sha256();
})

function resetInput(name){
    document.getElementsByName(name)[0].setCustomValidity("");
}

function setInput(name){
    document.getElementsByName(name)[0].setCustomValidity("Champ invalide")
}