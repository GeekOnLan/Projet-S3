<?php

require_once('includes/autoload.inc.php');
require_once('includes/utility.inc.php');
require_once('includes/connectedMember.inc.php');

$form = new GeekOnLanWebpage("GeekOnLan - Mes participations");
$form->appendCssUrl("style/regular/participer.css", "screen and (min-width: 680px)");
$form->appendCssUrl("style/mobile/participer.css", "screen and (max-width: 680px)");


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
	array_push ( $tableau, array(array($LAN->getLanName(),$LAN->getLanDate())));
	
	foreach($Membre->getTournoiParticiperFromLan($LAN->getId()) as $Tournoi){
		array_push ( $tableau[$i], array(array($Tournoi->getNomTournoi(),$Tournoi->getDateHeurePrevu())));
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

//var_dump($tableau[0]);

function getNbEquipeLAN($tableau,$i){
	$res=0;
	foreach($tableau[$i] as $lan){
		$j=1;
		while($j<sizeof($lan)){
			$res=$res+1;
			$j=$j+1;
		}
	}
	//var_dump($res-1);
	return $res-1;
}

$k=0;
$innerTableau="";
while($k<sizeof($tableau)){
	
	$nbEquipe=getNbEquipeLAN($tableau,$k);
	//var_dump($nbEquipe);
	$nomLan=$tableau[$k][0][0];//substr ( string $string , int $start [, int $length ] )
	$jL=substr($tableau[$k][0][1],0,2);
	$mL=substr($tableau[$k][0][1],3);
	
	$jT=substr($tableau[$k][1][0][1],0,2);
	$mT=substr($tableau[$k][1][0][1],3,7);
	$hT=substr($tableau[$k][1][0][1],12);
	$innerTableau.=<<<HTML
	
	<tr>
		<td rowspan="{$nbEquipe}">
			<div class="BlocksLan">
				<div class="Date">
					<span>{$jL}</span>
					<span>{$mL}</span>
				</div>
				<div class="Info">
					<span>{$nomLan}</span>
					<hr/>
				</div>
			</div>
			
		</td>
		<td>
			<div class="BlocksTournoi">
				<span>{$hT}</span>
				<div class="Date">
					<span>{$jT}</span>
					<span>{$mT}</span>
				</div>
				<div class="Info">
					<span>{$tableau[$k][1][0][0]}</span>
					<hr/>
				</div>
			</div>
			
		</td>
		<td>
			<div class="BlocksEquipe">
				<div class="gerer">
					<span>{$tableau[$k][1][1][0]}</span>
					<hr/>
					<a href="gererEquipe.php?idEquipe={$tableau[$k][1][1][0]}">Gérer</a>
				</div>
			</div>
		</td>
		<td>
		</td>
	</tr>
HTML
;
	$l=1;
	while($l<$nbEquipe){
		$jT=substr($tableau[$k][$l][0][1],0,2);
		$mT=substr($tableau[$k][$l][0][1],3,7);
		$hT=substr($tableau[$k][$l][0][1],12);
		$innerTableau.=<<<HTML
		
	<tr>
		<td>
			<div class="BlocksTournoi">
				<span>{$hT}</span>
				<div class="Date">
					<span>{$jT}</span>
					<span>{$mT}</span>
				</div>
				<div class="Info">
					<span>{$tableau[$k][$l][0][0]}</span>
					<hr/>
				</div>
			</div>
			
		</td>
		<td>
			<div class="BlocksEquipe">
				<div class="gerer">
					<span>{$tableau[$k][$l][1][0]}</span>
					<hr/>
					<a href="gererEquipe.php?idEquipe={$tableau[$k][$l][1][1]}">Gérer</a>
				</div>
			</div>
			
		</td>
		<td>
			
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
