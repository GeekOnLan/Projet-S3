<?php

require_once('includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - LAN");

$webpage -> appendContent('<div>Ceci est la page des LAN, Have fun!</div>');
echo $webpage->toHTML();