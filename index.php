<?php

require_once('includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Accueil");
$webpage->appendCssUrl("style/regular/accueil.css");

$lesLans = Lan::getLanFrom();
$news = "";

// Pour les mois écrit en français
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

foreach($lesLans as $lan){
    // On affecte l'image de la Lan, si elle n'en a pas, on en met une part défaut
    if($lan->getLanPicture()== null)$img = "resources/img/logo.png";
    else $img = $lan->getLanPicture();

    // La date avec des / c'est moche, donc on décompose la date pour faire un joli
    // style en CSS
    $date = explode('/', $lan->getLanDate());
    $day = $date[0];
    $month = ucfirst(strftime('%B', mktime(0, 0, 0, $date[1])));

    $news.=<<<HTML
        <table class="news">
            <tr>
                <td>
                    <div>
                        <span>$day</span>
                        <span>$month</span>
                    </div>
                    <img src="$img" alt="news" />
                </td>
                <td>
                    <h2>{$lan->getLanName()}</h2>
                    <hr/>
                    <p>{$lan->getLanDescription()}</p>
                    <a href="details.php?idLan={$lan->getId()}">Lire la suite</a>
                </td>
            </tr>
        </table>

HTML;
}

$webpage -> appendContent($news);
echo $webpage->toHTML();