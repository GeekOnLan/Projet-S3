<?php

require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Accueil");
$webpage->appendCssUrl("style/regular/notification.css");

$pdo = myPDO::getInstance();
$stmt = $pdo->prepare(<<<SQL
                SELECT *
                FROM Notifications n, Recevoir r
                WHERE n.idNotification = r.idNotification AND r.idMembre = :membre;
SQL
);
$stmt->execute(array("membre" => $_SESSION['Member']->getId()));
//$stmt->setFetchMode(PDO::FETCH_CLASS, 'Notifications');
$lesNotifs = $stmt->fetchAll();

$mesJoliesNotifs ="";
foreach($lesNotifs as $notif){
    $mesJoliesNotifs.=<<<HTML
    <div name="{$notif['idNotification']}">
        <h1>{$notif['objetNotif']}</h1>
        <h2>{$notif['dateNotif']}</h2>
        <p>{$notif['messageNotif']}</p>
        <button class="sB" name="supprimer"> Supprimer cette notification </button>
    </div>

HTML;
}
$webpage->appendContent($mesJoliesNotifs);
echo $webpage->toHTML();