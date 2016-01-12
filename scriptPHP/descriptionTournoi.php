<?php

if(!isset($_REQUEST["idTournoi"])||!is_numeric($_REQUEST['idLan'])){

}
else{
  echo $tournoi->getDescriptionTournoi()
}
