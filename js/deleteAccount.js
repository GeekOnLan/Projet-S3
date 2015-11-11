$(function() {
    toggleLayer.actions.push({
        actionClass: "deleteLayer",
        doAction: toggleDelete
    });

    // J'ai utiliser un seul selecteur pour le moment car tes deux boutons
    // font la même chose. Plus tard, il faudra faire 2 sélécteurs
    $("#myPrompt input").click(toggleDelete);
    $("#buttonDelete").click(toggleDelete);
});

var toggleDelete = function() {
    $("#myPrompt").toggleClass("open");
    $("body > div[id='layer']").toggleClass("deleteLayer");
};