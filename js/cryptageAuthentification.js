function sha256(){
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

function resetInput(name){
    document.getElementsByName(name)[0].style.borderColor = '';
}

function setInput(name){
    document.getElementsByName(name)[0].style.borderColor = 'red';
}