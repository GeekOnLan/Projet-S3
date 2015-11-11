function prompt(){
    if($("#myPrompt").hasClass("open")) {
        $("#myPrompt").removeAttr("class");
        $("body > div[id='layer']").removeAttr("class");
    } else {
        $("#myPrompt").addClass("open");
        $("body > div[id='layer']").addClass("deleteLayer");
    }
}

function clickOk(){
    //document.delete.submit();
    prompt();
}


function clickAnnuler(){
    prompt();
}