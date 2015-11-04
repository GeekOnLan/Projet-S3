function sha256(){
    pass = document.getElementsByName('pass')[0].value;
    log = document.getElementsByName('login')[0].value;
    if (pass == ''){
        document.getElementsByName('pass')[0].style.borderColor = 'red';
    }
    if(log == ''){
        document.getElementsByName('login')[0].style.borderColor = 'red';
    }
    if(log != '' && pass != ''){
        cryptpass = CryptoJS.SHA256(pass);
        document.getElementsByName('pass')[0].value = "";
        cryptlog = CryptoJS.SHA1(log);
        document.getElementsByName('login')[0].value = "";

        document.getElementsByName('hidden')[0].value = CryptoJS.SHA1(cryptlog + document.getElementsByName('hidden')[0].value + cryptpass);
        document.connexion.submit();
    }
}

function resetInput(name){
    document.getElementsByName(name)[0].style.borderColor = 'white';
}
