<?php
require_once('../includes/utility.inc.php');
require_once("../includes/myPDO.inc.php");
require_once('../classes/MyPDO.class.php');

//Fonction principale
$idTeam = $_GET['id'];
$choix = $_GET['choix'];
Member::getInstance();

if($choix) addTeam($idTeam);

var_dump($_SESSION);
deleteInvit($idTeam);



//Fonction d'ajout dans l'équipe
function addTeam($idTeam){
    $pdo = MyPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
    DELETE FROM composer WHERE idMembre = :idmembre;
SQL
    );
    $stmt->execute(array("idmembre" => $_SESSION['Member']->getId()));
    //Ajout

    $stmt = $pdo->prepare(<<<SQL
    INSERT INTO `composer`(`idMembre`, `idEquipe`, `role`) VALUES (:idmembre,:idteam,1)
SQL
    );
    $stmt->execute(array("idteam" => $idTeam,"idmembre" => $_SESSION['Member']->getId()));

    return 0;
}

//Fonction de suppression dans la BD
function deleteInvit($idTeam){
    $pdo = MyPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
    DELETE FROM inviter WHERE idEquipe = :idinvit AND idMembre = :idmembre;
SQL
    );
    $stmt->execute(array("idinvit" => $idTeam,"idmembre" => $_SESSION['Member']->getId()));
    return 0;
}


