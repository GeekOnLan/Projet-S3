<?php

require_once('includes/autoload.inc.php');
require_once('includes/myPDO.inc.php');
require_once('includes/utility.inc.php');

$page = new GeekOnLanWebpage("GeekOnLan - Inscription");
$page->appendCssUrl("style/regular/listeLans.css", "screen and (min-width: 680px");
$page->appendCssUrl("style/mobile/listeLans.css", "screen and (max-width: 680px");
$page->appendJsUrl("js/listeLans.js");

$page->appendContent(<<<HTML
    <form name="filter" onsubmit="return false;">
        <table>
            <tr>
                <td>
                    <p>Recherche par lieu</p>
                    <input type="text" name="departement" placeholder="Département">
                    <input type="text" name="ville" placeholder="Ville">
                </td>
                <td rowspan="2">
                    <label for="filterNom">Recherche par nom</label>
                    <input id="filterNom" type="text" name="name" placeholder="Nom de la LAN">
                    <button type="button" id="searchSubmit">Rechercher</button>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td><label for="filtreGratuit">Gratuit</label></td>
                            <td><input id="filtreGratuit" type="checkbox" name="gratuit"></td>
                            <td></td>
                            <td rowspan="3"><textarea id="filtreJeu" name="jeu" placeholder="Rechercher par jeu"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="filtreEquipe">Equipe</label></td>
                            <td><input id="filtreEquipe" type="checkbox" name="equipe"></td>
                            <td><label for="filtreJeu">Recherche par jeu</label></td>
                        </tr>
                        <tr>
                            <td><label for="filtreSolo">Seul</label></td>
                            <td><input id="filtreSolo" type="checkbox" name="solo"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
    <div>
        <button id="prevPage" type="button"> &lt </button>
        <button id="nextPage" type="button"> &gt </button>
    </div>
    <table>
        <thead>
            <tr>
                <td>Nom</td>
                <td>Date</td>
                <td>Lieu</td>
            </tr>
        </thead>
        <tbody id="searchRes">
        </tbody>
    </table>
HTML
);

echo $page->toHTML();
