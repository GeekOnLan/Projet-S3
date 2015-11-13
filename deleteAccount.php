<?php
require_once("includes/autoload.inc.php");
require_once("includes/myPDO.inc.php");
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

if(verify($_SERVER,'HTTP_REFERER')) {
    $member = Member::getInstance();

    $pdo = MyPDO::GetInstance();
    $stmt = $pdo->prepare(<<<SQL
			DELETE FROM Membre
            WHERE idMembre=:id;
SQL
    );
    $stmt->execute(array("id" => $member->getId()));
    Member::disconnect();
    header('Location: index.php');
}
else
    header('Location: index.php');
