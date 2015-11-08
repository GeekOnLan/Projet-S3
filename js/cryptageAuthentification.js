function sha256(){/*
    pass = document.getElementsByName('pass')[0].value;
    log = document.getElementsByName('login')[0].value;
    if (pass == ''){
        setInput('pass');
    }
    if(log == ''){
        setInput('login');
    }
    if(log != '' && pass != ''){
        cryptpass = CryptoJS.SHA256(pass);
        document.getElementsByName('pass')[0].value = "";
        document.getElementsByName('login')[0].value = "";
        crypt = CryptoJS.SHA1(log + document.getElementsByName('hidden')[0].value + cryptpass);
        xhr = new XMLHttpRequest();
        xhr.addEventListener('readystatechange', function () {
            if (xhr.readyState === 2) {
                alert('send');
            }
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseXML.documentElement);
            }
        }, true);
        xhr.open('GET', 'scriptPHP/connexion.php?crypt=' + crypt);
        xhr.send(null);
    }*/
   /* pass = document.getElementsByName('pass')[0].value;
    log = document.getElementsByName('login')[0].value;
    cryptpass = CryptoJS.SHA256(pass);
    document.getElementsByName('pass')[0].value = "";
    document.getElementsByName('login')[0].value = "";
    document.getElementsByName('hiddenCrypt')[0].value = CryptoJS.SHA1(log + document.getElementsByName('hiddenCrypt')[0].value + cryptpass);*/
    document.connexion.submit();
}

function resetInput(name){
    document.getElementsByName(name)[0].style.borderColor = '';
}

function setInput(name){
    document.getElementsByName(name)[0].style.borderColor = 'red';
}