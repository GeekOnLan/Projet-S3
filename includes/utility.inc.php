<?php
function verify($champs,$string){
    return (isset($champs[$string])&&!empty($champs[$string]));
}

function verifyForm($champs,$birthday,$fN,$lN){
    if(verify($champs,$birthday)){
        if(!mb_ereg("(?:(?:0[1-9]|[12][0-9])|(?:(?:0[13-9]|1[0-2])[\/\\-. ]?30)|(?:(?:0[13578]|1[02])[\/\\-. ]?31))[\/\\-. ]?(?:0[1-9]|1[0-2])[\/\\-. ]?(?:19|20)[0-9]{2}",$champs[$birthday]) == 1){
            return "errorBirthday";
        }
    }
    if(verify($champs,$fN)){
        if(!mb_ereg("^[a-zA-Z'ΰβιθκτωϋηοΐΒΙΘΤΩΫΗ \.]{0,40}$",$champs[$fN]) == 1){
            return "errorFirstNaame";
        }
    }
    if(verify($champs,$lN)){
        if(!mb_ereg("^[a-zA-Z'ΰβιθκτωϋηοΐΒΙΘΤΩΫΗ \.]{0,40}$",$champs[$lN]) == 1){
            return "errorLastName";
        }
    }
    return "OK";
}