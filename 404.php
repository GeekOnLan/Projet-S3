<?php

require_once("includes/autoload.inc.php");

$page = new GeekOnLanWebpage("Page not found");
$page->appendContent(<<<HTML
    <style>
        #mainframe div {
            text-align: center;
        }
    </style>
    <div>
        <img src="resources/img/404.png" alt="Dead link" />
    </div>
HTML
);
echo $page->toHTML();