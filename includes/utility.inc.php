<?php
function verify($champs,$string){
    return (isset($champs[$string])&&!empty($champs[$string]));
}

//Fonction pour les tests sur le formulaire d'inscription, surement r�utilisable
// Retourne 1 si le champ birthday est mal renseign�, 2 si c'est le champ du pr�nom, 3 pour le nom. Retourne 4 si les 3 champs sont correctement
//renseign�s.
function verifyForm($champs,$birthday,$fN,$lN){
    if(verify($champs,$birthday)){
        if(!mb_ereg("(?:(?:0[1-9]|[12][0-9])|(?:(?:0[13-9]|1[0-2])[\/\\-. ]?30)|(?:(?:0[13578]|1[02])[\/\\-. ]?31))[\/\\-. ]?(?:0[1-9]|1[0-2])[\/\\-. ]?(?:19|20)[0-9]{2}",$champs[$birthday]) == 1){
            return 1;
        }
    }
    if(verify($champs,$fN)){
        if(!mb_ereg("^[a-zA-Z]{1,20}$",$champs[$fN]) == 1){
            return 2;
        }
    }
    if(verify($champs,$lN)){
        if(!mb_ereg("^[a-zA-Z]{1,20}$",$champs[$lN]) == 1){
            return 3;
        }
    }
    return 4;
}