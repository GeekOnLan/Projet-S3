$(function() {
    toggleLayer.actions.push({
        actionClass: "deleteLayer",
        doAction: toggleDelete
    });
    $("#idConfirmer").click(function(){document.delete.submit()});
    $("#idAnnuler").click(toggleDelete);
    $("#buttonDelete").click(toggleDelete);
});

var toggleDelete = function() {
    $("#myPrompt").toggleClass("open");
    $("body > div[id='layer']").toggleClass("deleteLayer");
};