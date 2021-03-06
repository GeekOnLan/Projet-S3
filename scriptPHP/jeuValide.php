<?php
require_once("../includes/utility.inc.php");
require_once("../includes/autoload.inc.php");
require_once("../includes/myPDO.inc.php");

$xml = "<?xml version = \"1.0\" encoding=\"UTF-8\"?>";

if(verify($_GET,'NameJeu')){
    header('Content-Type: application/xml');
    usleep(rand(0, 20) * 100000);
    $pdo = MyPDO::GetInstance();
    $stmt = $pdo->prepare(<<<SQL
    SELECT nomJeu
    FROM Jeu
    WHERE nomJeu = :nomJeu;
SQL
    );
    $stmt->execute(array("nomJeu" => $_GET['NameJeu']));
    $jeu = $stmt->fetch();
    if($jeu!=null){
        echo $xml."<response>true</response>";
    }
	else
    	echo $xml."<response>false</response>";
}
else
    header('Location: message.php?message=un probl�me est survenu');
