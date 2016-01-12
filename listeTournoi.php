<?php
require_once('includes/autoload.inc.php');


if(isset($_REQUEST['idLan'])&&is_numeric($_REQUEST['idLan'])){

$lan= Lan::createFromId($_REQUEST['idLan']);

$page = new GeekOnLanWebpage("GeekOnLan -".$lan->getLanName()." - Liste des tournois");
$page->appendCssUrl("style/regular/listeTournoisMembre.css", "screen and (min-width: 680px");

$tournois=$lan->getTournoi();

if(sizeof($tournois)==0){
  $page->appendContent(<<<HTML
<table>
<tr>
  <th>Aucun tournoi prévu pour cette LAN</th>
</tr>

</table>
HTML
);
}
else{
  $page->appendContent(<<<HTML
  <table>
  	<tr>
  		<th>Nom</th>
  		<th>Date et heure prévu</th>
  		<th>Type Elimination</th>
  		<th>Nombre d équipe</th>
  		<th>Nombre de personnes par equipes</th>
  	</tr>
HTML
);

  foreach ($tournois as $tournoi){
    $page->appendContent(toString($tournoi));
  }

  $page->appendContent(<<<HTML

  <tr>
    <td colspan=5><a href="">Participer</a></td>
  </tr>

</table>"
HTML
);

}





echo $page->toHTML();
}
else{
  header('Location: message.php?message=un problème est survenu');
}
