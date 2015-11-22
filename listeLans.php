<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Inscription");
$page->appendCssUrl("style/regular/inscription.css", "screen and (min-width: 680px");
$page->appendCssUrl("style/mobile/inscription.css", "screen and (max-width: 680px");

$pageNumber = 1;
if(verify($_GET, 'page'))
    $pageNumber = $_GET['page'];

$list = Lan::getLanFromRange($pageNumber - 1, 10);

$html = <<<HTML
    <table>
HTML;

foreach($list as $lan) {
    $html .= <<<HTML
        <tr>
            <td>{$lan->getLanName()}</td>
            <td>{$lan->getLanDate()}</td>
            <td>{$lan->getLanDate()}</td>
            <td>{$lan->getLieux()}</td>
        </tr>
HTML;
}

$html .= <<<HTML
    </table>
HTML;

$page->appendContent($html);

echo $page->toHTML();