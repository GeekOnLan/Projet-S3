var xhr;
var envoitInvite;
if (window.XMLHttpRequest) {
    xhr = new XMLHttpRequest();
    envoitInvite = new XMLHttpRequest();
} else {
    // code for IE6, IE5
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
    envoitInvite = new ActiveXObject("Microsoft.XMLHTTP");
}

function envoyerInvite(){
    xhr.abort();
    var pseu = document.getElementsByName('pseudo')[0].value;
    if (pseu != '') {
        if(pseu.length>20){
            setInput('pseudo');
            setError('erreurpseudo', 'Le pseudonyme doit contenir entre 2 et 20 caractères');
        }
        else{
            document.getElementById("pseudoLogo").setAttribute("src","resources/gif/load.gif");
            xhr.addEventListener('readystatechange', function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("pseudoLogo").setAttribute("src","resources/img/Contact.png");
                    var xml = xhr.responseXML.getElementsByTagName('response').item(0).textContent;
                    if (xml == "true") {
                        setInput('pseudo');
                        setError('erreurpseudo', 'Ce pseudonyme est inconnu');
                    }
                    else
                        document.formInvite.submit();
                }
            }, true);
            xhr.open('GET', 'scriptPHP/pseudoValide.php?wait&pseudo=' + pseu);
            xhr.send(null);
        }
    }
    else{
        setError('erreurpseudo', '  ');
    }
}


//met les erreur sur le css
function setInput(name){
    document.getElementsByName(name)[0].setCustomValidity("Champs invalide")
}

//remet les coutours pas default
function resetInput(name){
    document.getElementsByName(name)[0].setCustomValidity(" ");
}

//affiche un message d'erreur
function setError(name,error){
    document.getElementById(name).innerHTML = error;
}
//enleve les erreurs
function resetError(name){
    document.getElementById(name).innerHTML = '';
}

function resetPseudo(){
    resetError('erreurpseudo');
    resetInput('pseudo');
}

function verifiPseudo() {
    xhr.abort();
    var pseu = document.getElementsByName('pseudo')[0].value;
    if (pseu != '') {
        if(pseu.length>20){
            setInput('pseudo');
            setError('erreurpseudo', 'Le pseudonyme doit contenir entre 2 et 20 caractères');
        }
        else{
            document.getElementById("pseudoLogo").setAttribute("src","resources/gif/load.gif");
            xhr.addEventListener('readystatechange', function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("pseudoLogo").setAttribute("src","resources/img/Contact.png");
                    var xml = xhr.responseXML.getElementsByTagName('response').item(0).textContent;
                    if (xml == "true") {
                        setInput('pseudo');
                        setError('erreurpseudo', 'Ce pseudonyme est inconnu');
                    }
                    else
                        resetPseudo();
                }
            }, true);
            xhr.open('GET', 'scriptPHP/pseudoValide.php?wait&pseudo=' + pseu);
            xhr.send(null);
        }
    }
    else{
        setError('erreurpseudo', '  ');
    }
}