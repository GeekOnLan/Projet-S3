<?php

require_once('includes/connectedPage.php');
require_once('includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Profile");

$webpage -> appendContent('<div>Ceci est la de ton profile</div>');
echo $webpage->toHTML();