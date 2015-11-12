$(function() {
    toggleLayer.actions.push({
        actionClass: "deleteLayer",
        doAction: toggleDelete
    });

    $("#buttonDelete").click(toggleDelete);
});

var toggleDelete = function() {
    $("#myPrompt").toggleClass("open");
    $("body > div[id='layer']").toggleClass("deleteLayer");
};