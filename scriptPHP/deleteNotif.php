<?php
require_once('../includes/utility.inc.php');
require_once("../includes/myPDO.inc.php");
require_once('../classes/MyPDO.class.php');


//Fonction principale

$id = $_GET['id'];
Member::getInstance();
var_dump($_SESSION);
deleteNotif($id);


//Fonction de suppression dans la BD
function deleteNotif($idNotif){
    $pdo = MyPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
    DELETE FROM Recevoir WHERE idNotification = :idnotif AND idMembre = :idmembre;
SQL
    );

    $stmt->execute(array("idnotif" => $idNotif,"idmembre" => $_SESSION['Member']->getId()));
    return 0;
}


