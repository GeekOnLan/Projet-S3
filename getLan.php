<?php
require_once("includes/utility.inc.php");
require_once("includes/autoload.inc.php");
require_once("includes/myPDO.inc.php");

if(verify($_GET,'idLan')){
/*	header('Content-Type: application/json');
	usleep(rand(0, 20) * 100000);*/
	$lan=Lan::createFromId($_GET['idLan']);
	/*$lieu=$lan->getLieux();
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
	echo json_encode($info);*/
	$open= "non";
	if($lan->isOpen()) $open = "oui";
	$html = <<<HTML
	<table>
		<tr>
			<td>Adresse</td>
			<td>{$lan->getAdress()}</td>
		</tr>
		<tr>
			<td>Ouverte ?</td>
			<td>{$open}</td>
		</tr>
		<tr>
			<td>Nom Simple</td>
			<td>{$lan->getLieux()->getNomSimple()}</td>
		</tr>
		<tr>
			<td>Ville</td>
			<td>{$lan->getLieux()->getNomVille()}</td>
		</tr>
		<tr>
			<td>Arrondissement</td>
			<td>{$lan->getLieux()->getArrondissement()}</td>
		</tr>
		<tr>
			<td>Code Postal</td>
			<td>{$lan->getLieux()->getCodePostal()}</td>
		</tr>
		<tr>
			<td>Slug</td>
			<td>{$lan->getLieux()->getSlug()}</td>
		</tr>
		<tr>
			<td>Departement</td>
			<td>{$lan->getLieux()->getDepartement()}</td>
		</tr>
		<tr>
			<td>Canton</td>
			<td>{$lan->getLieux()->getCanton()}</td>
		</tr>
	</table>
HTML;

	echo $html;
}
else
	header('Location: message.php?message=un problème est survenu');
