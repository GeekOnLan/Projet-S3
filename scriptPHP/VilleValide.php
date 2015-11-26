<?php
require_once("../includes/utility.inc.php");
require_once("../includes/autoload.inc.php");
require_once("../includes/myPDO.inc.php");

$xml = "<?xml version = \"1.0\" encoding=\"UTF-8\"?>";

if(verify($_GET,'Ville')){
    header('Content-Type: application/xml');
    usleep(2 * 100000);
    $pdo = MyPDO::GetInstance();
    $stmt = $pdo->prepare(<<<SQL
    SELECT nomVille
    FROM lieu
    WHERE nomVille = :nomVille;
SQL
    );
    $stmt->execute(array("nomVille" => $_GET['Ville']));
    $ville = $stmt->fetch();
    if($ville!=null){
        echo $xml."<response>true</response>";
    }
else
    echo $xml."<response>false</response>";
}
else
    header('Location: ../erreur.php?erreur=un problème est survenu');
