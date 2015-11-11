<?php

require_once('includes/connectedPage.inc.php');
require_once('includes/autoload.inc.php');

$webpage = new GeekOnLanWebpage("GeekOnLan - Profile");
$webpage->appendJsUrl("js/deleteAccount.js");
$webpage->appendCssUrl("style/regular/deleteAccount.css","screen and (min-width: 680px");
$webpage->appendCssUrl("style/mobile/deleteAccount.css","screen and (max-width: 680px)");

//list a puce des informations du membre
$html = '<ul>';

$member = Member::getInstance();

//information relaive au membre
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

//boutton du suppression du compte
$html.="<button type=\"button\" onclick=\"prompt()\">supprimer ce compte</button>";

//boite de dialogue pour confirmer la suppression
$prompt=<<<HTML
<div id="myPrompt">
	<div id="promptTitle">
       <h1>Supprimer ce compte ?</h1>
  	</div>
    <div id="promptButton">
     	<input type="button" value="Confirmer" id="idOk" class="ok" onclick="clickOk()" />
      	<input type="button" value="Annuler" id="idAnnuler" class="annuler" onclick="clickAnnuler()" />
  </div>
</div>
HTML;


$webpage -> appendContent($html);
$webpage -> appendForeground($prompt);
echo $webpage->toHTML();