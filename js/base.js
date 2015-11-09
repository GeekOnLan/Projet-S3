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
            $("body > div[id='mainframe']").addClass("pushedMainframe blur");
            $("body > div[id='layer']").addClass("foregroundLayer");
        }
    }

    var toggleLayer = function() {
        if($("body > div[id='layer']").hasClass("foregroundLayer")) {
            toggleSidebar();
        } else if($("body > div[id='layer']").hasClass("backgroundLayer")) {
            toggleConnexionForm();
        }
    }

    var toggleConnexionForm = function() {
        if($("#connexionForm").hasClass("open")) {
            $("#connexionForm").removeAttr("class");
            $("body > div[id='layer']").removeAttr("class");
            $("body > div[id='mainframe']").removeAttr("class");
        } else {
            $("#connexionForm").addClass("open");
            $("body > div[id='layer']").addClass("backgroundLayer");
            $("body > div[id='mainframe']").addClass("blur");
        }
    }

    $("#sidebarButton").click(toggleSidebar);
    $("#layer").click(toggleLayer);
    $("#connexionButton").click(toggleConnexionForm);
});