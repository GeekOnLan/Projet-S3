<?php

require_once('includes/autoload.inc.php');

$webpage = new Webpage("GeekOnLan - Profile");
$webpage->appendBasicCSSAndJS();

$webpage -> appendContent('<div>Ceci est la de ton profile</div>');
echo $webpage->toHTML();