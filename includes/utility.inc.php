<?php
function verify($champs,$string){
    return (isset($champs[$string])&&!empty($champs[$string]));
}

function decrypt($chaine){
	
	$n = "22782469609880586210215954529198328699762849071829281444083913073420430341988274543115439207346717733103808548322724893717891249991955541197995203978840775836853914854777304089361538296383477248076248762831253054689313750618030258372535915451171672561015357101019001878811374043380853820209273845487370257454447664096573362294553079927349217490049751330678460389718079215427407035320036101073797491427224454671279438924759398949863132362128519457260723263409621194840838762296330112075635352615276651650471297528264099124381803584728419013555202008843348423786688917724709218090042590673112815023946036299048884777319";
	$d = "16863892142460934035317642529855398750759359960798034025306125189936116168664320584571354327158130715734453547600996513162975973008574086372610726242426088673036226283396095224543720515199366202510018335061359752359382497351133373161044600795648506515622552071803684526566253923300109071591815967425589541120380110940899509560256364819219749625511372871265229964849424291443392537003638327363951592391423082258221423298092579778487816388076144382245100982438588752691016970506995535562546170900126911522021267540307044186517438503107686602382581786929426966449574961139976344555148771573203264389121028266862710266593";
	
	$chaine=bcpowmod($chaine,$d,$n);
	
	$digitsStr='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_=!@#$%^&*()[]{}|;:,.<>/?`~ \\\'\"+-';
	$res='';
	while($chaine.length>=0){
		if($chaine.length>=2){
			$temp=substr($chaine,$chaine.length-2,2);
			$chaine=substr($chaine, 0,$chaine.length-2);
			$res=$res.$digitsStr[$temp];
		}else{
			$temp=$chaine[0];
			$chaine="";
			$res=$res.temp;
		}
	}
	
	return $res;
}
