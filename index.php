<?php

require_once('includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Accueil");
$webpage->appendCssUrl("style/regular/accueil.css");
$lesLans = Lan::getLanFrom();
$news = "";

foreach($lesLans as $lan){
    $inutile = Lan::createFromId($lan['idLAN']);
    $news.= "<div class='inutile'>\n     <div class=\"dateDiv\">".$inutile->getLanDate()."</div>";
    $news.="\n       <div class=\"news\">";
    $news.="\n          <img class=\"imgDiv\" src=\"".$inutile->getLanPicture()."\">";
    $news.="\n     <div class =\"txtDiv\">".$inutile->getLanName()."</div>\n   </div>\n</div>\n";
}

$webpage -> appendContent('<div>Ceci est la page d\'accueil, Have fun! Un texte un peu plus long pour pouvoir vérifier l\'alignement. Ah non c\'est toujours pas assez long, donc je vais devoir vous raconter une longue histoire</div>');
$webpage -> appendContent($news);
echo $webpage->toHTML();