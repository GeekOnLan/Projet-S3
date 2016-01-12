<?php

require_once("includes/autoload.inc.php");
require_once("includes/utility.inc.php");

$page = new GeekOnLanWebpage("GeekOnLan - Match");
$lans = Member::getInstance()->getLAN();

$lan = null;
$tournoi = null;

if(isset($_GET["idLan"]) && is_numeric($_GET["idLan"]) && $_GET["idLan"] < count($lans)) {
    $lan = $lans[$_GET["idLan"]];
    if (isset($_GET["idTournoi"]) && is_numeric($_GET["idTournoi"])) {
        $tournoi = $lan->getTournoi()[$_GET["idTournoi"]];
    } else {
        header('Location: message.php?message=un problème est survenu');
    }
} else {
    header('Location: message.php?message=un problème est survenu');
}

$equipes = $tournoi->getEquipe();
$html = <<<HTML
<div id="tree">
HTML;

$nbEquipes = count($equipes);

for($i = 1; $i <= log($nbEquipes) / log(2); $i++) {
    $html .= "<table><tr>";
    for($j = 0; $j < pow(2, $i); $j++) {
        $html .= "<td>pd</td>";
    }
    $html .= "</tr></table>";
}

$html .= "</div>";

$page->appendContent($html);

echo $page->toHTML();
