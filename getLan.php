<?php
require_once("includes/utility.inc.php");
require_once("includes/autoload.inc.php");

$xml = "<?xml version = \"1.0\" encoding=\"UTF-8\"?>";

if(verify($_GET,'idLan')){
	header('Content-Type: application/xml');
	usleep(rand(0, 20) * 100000);
	$lan=Lan::createFromId($_GET['idLan']);
	$lieu=$lan->getLieux();
	$info=<<<XML
<lan>
	<nom>{$lan->getLanName()}</nom>
	<date>{$lan->getLanDate()}</date>
	<adresse>{$lan->getAdress()}</adresse>
	<open>{$lan->isOpen()}</open>
	<description>{$lan->getLanDescription()}</description>	
	<lieu>
		<nomSimple>{$lieu->getNomSimple()}</nomSimple>
		<nomVille>{$lieu->getNomVille()}</nomVille>
		<arrondissement>{$lieu->getArrondissement()}</arrondissement>
		<code>{$lieu->getCodePostal()}</code>
		<canton>{$lieu->getCanton()}</canton>
		<slug>{$lieu->getSlug()}</slug>
		<departement>{$lieu->getDepartement()}</departement>
	</lieu>
</lan>
XML;
			echo $xml.$info;
}
else
	header('Location: ../erreur.php?erreur=un probléme est survenu');