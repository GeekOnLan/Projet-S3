<?php
require_once("../includes/autoload.inc.php");
require_once("../includes/myPDO.inc.php");

$member = Member::getInstance();

$pdo = MyPDO::GetInstance();
$stmt = $pdo->prepare(<<<SQL
			DELETE FROM Membre
            WHERE idMembre=:id;
SQL
);
$stmt->execute(array("id"=>17));
Member::disconnect();
header('Location: ../index.php');