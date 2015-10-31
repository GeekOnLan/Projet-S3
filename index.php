<?php

require_once('includes/autoload.inc.php');

$webpage = new Webpage("GeekOnLan - Accueil");
$webpage->appendCssUrl("style/header.css");
$webpage->appendCssUrl("style/accueil.css");

echo $webpage->toHTML();