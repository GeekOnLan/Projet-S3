$(function() {
    $("#menu a").each(function () {
        this.title = this.innerHTML;
    });

    toggleLayer.actions = [];
    toggleLayer.actions.push({
        actionClass: "sidebarLayer",
        doAction: toggleSidebar
    });

    toggleLayer.actions.push({
        actionClass: "connexionLayer",
        doAction: toggleConnexionForm
    });

    $("#sidebarButton").click(toggleSidebar);
    $("#layer").click(toggleLayer);
    $("#connexionButton").click(toggleConnexionForm);
    $("#connexionForm a:nth-child(2)").click(toggleConnexionForm);
});

var toggleSidebar = function() {
    $("body > nav[id='sidebar']").toggleClass("sidebarOpen");
    $("body > div[id='mainframe']").toggleClass("pushedMainframe");
    $("body > div[id='layer']").toggleClass("sidebarLayer");
    stopParallax = !stopParallax;
};

var toggleLayer = function() {
    var layer = $("body > div[id='layer']");
    toggleLayer.actions.forEach(function(action) {
        if(layer.hasClass(action.actionClass))
            action.doAction();
    });
};

var toggleConnexionForm = function() {
    $("#connexionForm").toggleClass("open");

    $("body > div[id='layer']").toggleClass("connexionLayer");
    $("body > div[id='mainframe']").toggleClass("slide");
    stopParallax = !stopParallax;
};
