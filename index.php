<?php

require_once('includes/autoload.inc.php');

$webpage = new Webpage("GeekOnLan - Accueil");
$webpage -> appendContent('<div>Ceci est la page d\'accueil, Have fun!</div>');
echo $webpage->toHTML();