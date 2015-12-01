<?php
require_once("includes/utility.inc.php");
require_once("includes/autoload.inc.php");

if(verify($_GET,'idLan')){
//	header('Content-Type: application/json');
	usleep(rand(0, 20) * 100000);
	$lan=Lan::createFromId($_GET['idLan']);
	$lieu=$lan->getLieux();
	$info=array();
	$info[]+=$lan->getLanName();
	$info[]+=$lan->getLanDate();
	$info[]+=$lan->getAdress();
	$info[]+=$lan->isOpen();
	$info[]+=$lan->getLanDescription();
	/*$lieu=array();
	$lieu+=$lieu->getNomSimple();
	$lieu+=$lieu->getNomVille();
	$lieu+=$lieu->getArrondissement();
	$lieu+=$lieu->getCodePostal();
	$lieu+=$lieu->getCanton();
	$lieu+=$lieu->getSlug();	
	$lieu+=$lieu->getDepartement();	
	$info+=$lieu;*/
		var_dump($info);
}
else
	echo "lol";//header('Location: ../erreur.php?erreur=un probléme est survenu');
