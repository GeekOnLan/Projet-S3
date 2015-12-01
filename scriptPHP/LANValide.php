<?php
require_once("../includes/utility.inc.php");
require_once("../includes/autoload.inc.php");
require_once("../includes/myPDO.inc.php");

$xml = "<?xml version = \"1.0\" encoding=\"UTF-8\"?>";

if(verify($_GET,'LANName')){
    header('Content-Type: application/xml');
    usleep(rand(0, 20) * 100000);
    $pdo = MyPDO::GetInstance();
    $stmt = $pdo->prepare(<<<SQL
                SELECT nomLAN
                FROM LAN
                WHERE nomLAN = :nomLAN;
SQL
    );
    $stmt->execute(array("nomLAN" => $_GET['LANName']));
    $member = $stmt->fetch();
    if($member==false){
        echo $xml."<response>true</response>";
    }
    else
        echo $xml."<response>false</response>";
}
else
    header('Location: ../erreur.php?erreur=un probléme est survenu');
