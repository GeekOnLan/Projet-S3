<?php

require_once('../includes/myPDO.inc.php');
require_once('../includes/requestUtils.inc.php');
require_once('../includes/autoload.inc.php');

//header('Content-Type: text/text');


if(!isset($_REQUEST["idTournoi"])||!is_numeric($_REQUEST["idTournoi"])||!isset($_REQUEST["idLan"])||!is_numeric($_REQUEST["idLan"])){
  echo "Parametres incorrects";
}
else{
  try{
    $tournoi= Tournoi::getTournoiFromLAN($_REQUEST["idLan"],$_REQUEST["idTournoi"]);
    echo $tournoi->getDescriptionTournoi();
  }
  catch(Exception $e){
    echo "Pas de tournoi";
  }
}
