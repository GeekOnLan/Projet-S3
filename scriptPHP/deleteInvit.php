<?php
require_once('../includes/utility.inc.php');
require_once("../includes/myPDO.inc.php");
require_once('../classes/MyPDO.class.php');

if (!verify($_SERVER, 'HTTP_REFERER'))
    header('Location: ../message.php?message=un problème est survenu');

if (!isset($_GET['id']) || empty($_GET['id']) || !isset($_GET['choix']) || empty($_GET['choix']))
    header('Location: ../message.php?message=un problème est survenu');

//Fonction principale
$idTeam = $_GET['id'];
$choix = $_GET['choix'];
Member::getInstance();
deleteInvit($idTeam);
if($choix=="true"){
    addTeam($idTeam);
}

//Fonction d'ajout dans l'�quipe
function addTeam($idTeam){
    try {
        Equipe::createFromId($idTeam)->rejoindre(Member::getInstance()->getId());
    }catch (Exception $e){
        header('Location: ../message.php?message=Vous ete deja dans cette equipe');
    }
    return 0;
}

//Fonction de suppression dans la BD
function deleteInvit($idTeam){
    $pdo = MyPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
    DELETE FROM Inviter WHERE idEquipe = :idinvit AND idMembre = :idmembre;
SQL
    );
    $stmt->execute(array("idinvit" => $idTeam,"idmembre" => Member::getInstance()->getId()));
    return 0;
}


