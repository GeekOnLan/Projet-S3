function sha256(){
    pass = document.getElementsByName('pass')[0].value;
    cryptpass = CryptoJS.SHA256(pass);
    document.getElementsByName('hiddenpass')[0].value=cryptpass;
    document.getElementsByName('pass')[0].value="";

    document.getElementsByName('hiddenlogin')[0].value=document.getElementsByName('login')[0].value;
    document.getElementsByName('login')[0].value="";
    document.forms['connexion'].submit();
}