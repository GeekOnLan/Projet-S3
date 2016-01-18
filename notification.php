<?php

require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Notification");
$webpage->appendCssUrl("style/regular/notification.css");
$webpage->appendJsUrl("js/notification.js");
$webpage->appendJsUrl("js/inscriptionNotif.js");


if(getNotification() != null)$webpage->appendContent(getNotification());
else $webpage->appendContent("<p class=\"vide\"> Pas de nouvelles notifications.</p>");

$webpage->appendContent("<hr>");

if(getInvitation() != null)$webpage->appendContent(getInvitation());
else $webpage->appendContent("<p class=\"vide\"> Pas de nouvelles invitations.</p>");

echo $webpage->toHTML();

function getNotification(){
$pdo = myPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
                SELECT *
                FROM Notifications n, Recevoir r
                WHERE n.idNotification = r.idNotification AND r.idMembre = :membre;
SQL
    );
    $stmt->execute(array("membre" => $_SESSION['Member']->getId()));
    $lesNotifs = $stmt->fetchAll();

    $mesJoliesNotifs ="";
    foreach($lesNotifs as $notif){
        $mesJoliesNotifs.=<<<HTML
    <div class="notif" id="{$notif['idNotification']}">
        <h1>{$notif['objetNotif']}</h1>
        <h2>{$notif['dateNotif']}</h2>
        <p>{$notif['messageNotif']}</p>
        <button class="sB"> Supprimer cette notification </button>
    </div>

HTML;
    }
    return $mesJoliesNotifs;
}

function getInvitation(){
    $pdo = myPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
                SELECT e.nomEquipe, e.idEquipe
                FROM Inviter i, Equipe e
                WHERE i.idMembre = :membre AND i.idEquipe = e.idEquipe;
SQL
    );
    $stmt->execute(array("membre" => $_SESSION['Member']->getId()));
    $lesInvits = $stmt->fetchAll();

    $mesJoliesInvits ="";
    foreach($lesInvits as $invit){
        $mesJoliesInvits.=<<<HTML
    <div class="invit" id="{$invit['idEquipe']}">
        <h1>L'équipe {$invit['nomEquipe']} vous invite é la rejoindre !</h1>
        <h2>Acceptez-vous cette invitation ?</h2>
        <button class="yB"> Oui </button> <button class="nB"> Non </button>
    </div>

HTML;
    }
    return $mesJoliesInvits;
}
