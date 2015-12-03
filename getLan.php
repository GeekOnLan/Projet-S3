<?php
require_once("includes/utility.inc.php");
require_once("includes/autoload.inc.php");

if(verify($_GET,'idLan')){
	header('Content-Type: application/json');
	usleep(rand(0, 20) * 100000);
	$lan=Lan::createFromId($_GET['idLan']);
	$lieu=$lan->getLieux();
	$info["name"]=$lan->getLanName();
	$info["date"]=$lan->getLanDate();
	$info["adresse"]=$lan->getAdress();
	$info["open"]=$lan->isOpen();
	$info["description"]=$lan->getLanDescription();
	$info["nomSimple"]=$lieu->getNomSimple();
	$info["nomVille"]=$lieu->getNomVille();
	$info["arrondissement"]=$lieu->getArrondissement();
	$info["code"]=$lieu->getCodePostal();
	$info["slug"]=$lieu->getSlug();	
	$info["departement"]=$lieu->getDepartement();
	$info["canton"]=$lieu->getCanton();
	echo json_encode($info);
}
else
	header('Location: message.php?message=un problème est survenu');
