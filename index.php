<?php

require_once('includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Accueil");
$webpage->appendCssUrl("style/regular/accueil.css");
$lesLans = Lan::getLanFrom();
$news = <<<HTML
<div id="news">
    <table>
        <tr><td>Les prochaines LAN</td></tr>\n
HTML;

foreach($lesLans as $lan){
   $news.="        <tr><td>".Lan::createFromId($lan['idLAN'])->getLanName()."</br>".Lan::createFromId($lan['idLAN'])->getLanDescription()."</td></tr>\n";
}


$news.=<<<HTML
    </table>
</div>
HTML;

$webpage -> appendContent('<div>Ceci est la page d\'accueil, Have fun! Un texte un peu plus long pour pouvoir vérifier l\'alignement. Ah non c\'est toujours pas assez long, donc je vais devoir vous raconter une longue histoire</div>');
$webpage -> appendContent($news);
echo $webpage->toHTML();