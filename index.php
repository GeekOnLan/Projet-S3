<?php

require_once('includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Accueil");
$webpage->appendCssUrl("style/regular/accueil.css");

$lesLans = Lan::getRecentLan();
$news = "";

// Pour les mois écrit en français
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

foreach($lesLans as $lan){
    $img = $lan->getLanPicture();

    // Si il n'y a pas d'image, on en met une part défaut
    if($img == null)
        $img = "resources/img/logo.png";

    // La date avec des '/' c'est moche, donc on décompose la date pour faire un joli
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
                    <div>
                        <h2>{$lan->getLanName()}</h2>
                        <hr/>
                        <p>{$lan->getLanDescription()}</p>
                        <a href="details.php?idLan={$lan->getId()}">Lire la suite</a>
                    </div>
                </td>
            </tr>
        </table>

HTML;
}

$webpage -> appendContent($news);
echo $webpage->toHTML();
