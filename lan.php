<?php

require_once('includes/autoload.inc.php');

$webpage = new Webpage("GeekOnLan - Accueil");
$webpage -> appendContent('<div>Ceci est la page des LAN, Have fun!</div>');
echo $webpage->toHTML();