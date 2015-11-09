<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Projet-S3/includes/connectedPage.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Projet-S3/includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Profile");

$html = '<ul>';

$member = Member::getInstance();

$html.='<li>'.$member->getPseudo().'</li>';
$last = $member->getLastName();
if($last!='')
	$html.='<li>'.$last.'</li>';
$first = $member->getFirstName();
if($first!='')
	$html.='<li>'.$first.'</li>';
$html.='<li>'.$member->getMail().'</li>';
$birth = $member->getBirthday();
if($birth!='')
	$html.='<li>'.$birth.'</li>';
$html.='</ul>';


$webpage -> appendContent($html);
echo $webpage->toHTML();