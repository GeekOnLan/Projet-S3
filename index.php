<?php

require_once('includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Accueil");

$webpage -> appendContent('<div>Ceci est la page d\'accueil, Have fun! Un texte un peu plus long pour pouvoir vérifier l\'alignement. Ah non c\'est toujours pas assez long, donc je vais devoir vous raconter une longue histoire</div>');
echo $webpage->toHTML();