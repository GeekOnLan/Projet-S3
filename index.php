<?php

require_once('includes/autoload.inc.php');

$webpage = new Webpage("GeekOnLan - Accueil");

echo $webpage->toHTML();