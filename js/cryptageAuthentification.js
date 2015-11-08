function sha256(){
    pass = document.getElementsByName('pass')[0].value;
    log = document.getElementsByName('login')[0].value;
    cryptpass = CryptoJS.SHA256(pass);
    document.getElementsByName('pass')[0].value = "";
    document.getElementsByName('login')[0].value = "";
    document.getElementsByName('hiddenCrypt')[0].value = CryptoJS.SHA1(log + cryptpass);
    document.connexion.submit();
}

function resetInput(name){
    document.getElementsByName(name)[0].style.borderColor = 'white';
}

function setInput(name){
    document.getElementsByName(name)[0].style.borderColor = 'red';
}