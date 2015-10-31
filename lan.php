<?php

require_once('includes/autoload.inc.php');

$webpage = new Webpage("GeekOnLan - LAN");
$webpage->appendCssUrl("style/header.css");
$webpage->appendCssUrl("style/accueil.css");
$webpage -> appendContent('<div>Ceci est la page des LAN, Have fun!</div>');
echo $webpage->toHTML();