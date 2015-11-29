<?php

require_once('includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Accueil");
$webpage->appendCssUrl("style/regular/accueil.css");
$lesLans = Lan::getLanFrom();
$news = "";

foreach($lesLans as $lan){
    $inutile = Lan::createFromId($lan['idLAN']);
    $news.=<<<HTML
        <div class="inutile">
            <div class="dateDiv">{$inutile->getLanDate()}</div>
            <div class="news">
                <img class="imgDiv" src="{$inutile->getLanPicture()}">
                <div class ="txtDiv">
                    <h1>{$inutile->getLanName()}</h1>
                </div>
            </div>
        </div>

HTML;
}

$webpage -> appendContent('<div>Ceci est la page d\'accueil, Have fun! Un texte un peu plus long pour pouvoir vérifier l\'alignement. Ah non c\'est toujours pas assez long, donc je vais devoir vous raconter une longue histoire</div>');
$webpage -> appendContent($news);
echo $webpage->toHTML();