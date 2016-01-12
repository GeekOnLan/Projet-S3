<?php

require_once('../includes/autoload.inc.php');

if(!isset($_REQUEST["idTournoi"])||!is_numeric($_REQUEST["idTournoi"])||!isset($_REQUEST["idLan"])||!is_numeric($_REQUEST["idLan"])){
  header('Location: message.php?message=Un probleme est survenu');
}
else{
  $tournoi= Tournoi::getTournoiFromLAN($_REQUEST["idLan"],$_REQUEST["idTournoi"]);
  echo $tournoi->getDescriptionTournoi();
}
