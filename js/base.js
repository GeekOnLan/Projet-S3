$(document).ready(function() {
    $("#menu a").each(function () {
        this.title = this.innerHTML;
    });

    var toggleSidebar = function() {
        if($("body > nav[id='sidebar']").hasClass("sidebarOpen")) {
            $("body > nav[id='sidebar']").removeAttr("class");
            $("body > div[id='mainframe']").removeAttr("class");
            $("body > div[id='layer']").removeAttr("class");
        } else {
            $("body > nav[id='sidebar']").addClass("sidebarOpen");
            $("body > div[id='mainframe']").addClass("pushedMainframe");
            $("body > div[id='layer']").addClass("foregroundLayer");
        }
    }

    $("#sidebarButton").click(toggleSidebar);
    $("#layer").click(toggleSidebar);
});