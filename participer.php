<?php

require_once('includes/autoload.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/connectedMember.inc.php');

$form = new GeekOnLanWebpage("GeekOnLan - Mes participations");
$form->appendCssUrl("style/regular/participation.css", "screen and (min-width: 680px");
$form->appendCssUrl("style/mobile/participation.css", "screen and (max-width: 680px");


function getTableau(){

//lan (nomlan,tournoi,tournoi,...)
//getLanParticiper()

//tournoi (nomtournoi,equipe,equipe)
//getTournoiParticiperFromLan($idLan)

//equipe (nomEquipe, id equipe)
//getEquipeParticiperFromLanAndTournoi($idLan,$idTournoi)
$tableau = array();
 $i=0;
 $j=1;
$Membre = Member::getInstance();

foreach($Membre->getLanParticiper() as $LAN){
	$nom = $LAN->getLanName();
	array_push ( $tableau, array($nom));
	
	foreach($Membre->getTournoiParticiperFromLan($LAN->getId()) as $Tournoi){
		array_push ( $tableau[$i], array($Tournoi->getNomTournoi()));
		foreach($Membre->getEquipeParticiperFromLanAndTournoi($LAN->getId(),$Tournoi->getIdTournoi())as $Equipe){
			array_push ( $tableau[$i][$j], array($Equipe->getNomEquipe(),$Equipe->getIdEquipe()));
		}
		$j=$j+1;
	}
	$j=1;
	$i=$i+1;
}
/*
$exemple = array(
			array(
				"lan1",
				array("tournoi1",array("equipe1",1),array("equipe2",2)),
				array("tournoi2",array(),array())
				)
			);
*/
 return $tableau;
}

$tableau=getTableau();

function getNbEquipeLAN($tableau,$i){
	$res=0;
	foreach($tableau[$i] as $tournoi){
		$j=1;
		while($j<sizeof($tournoi)){
			$res=$res+1;
			$j=$j+1;
		}
	}
	return $res;
}
$k=0;
$innerTableau="";
while($k<sizeof($tableau)){
	
	$nbEquipe=getNbEquipeLAN($tableau,$k);
	$nomLan=$tableau[$k][0];
	$innerTableau.=<<<HTML
	
	<tr>
		<td rowspan="{$nbEquipe}">
			{$nomLan}
		</td>
		<td>
			{$tableau[$k][1][0]}
		</td>
		<td>
			{$tableau[$k][1][1][0]}
		</td>
		<td>
			<a href="gererEquipe.php?idEquipe={$tableau[$k][1][1][1]}">gerer</a>
		</td>
	</tr>
HTML
;
	$l=2;
	while($l<=$nbEquipe){
		
		$innerTableau.=<<<HTML
		
	<tr>
		<td>
			{$tableau[$k][$l][0]}
		</td>
		<td>
			{$tableau[$k][$l][1][0]}
		</td>
		<td>
			<a href="gererEquipe.php?idEquipe={$tableau[$k][$l][1][1]}">gerer</a>
		</td>
	</tr>
	
HTML
;
		$l=$l+1;
	}
	$k=$k+1;
}

$form->appendContent(<<<HTML
    <table class="lanForm">
        <thead>
        </thead>
        <tbody>
            {$innerTableau}
        </tbody>
    </table>
HTML
);

echo $form->toHTML();
