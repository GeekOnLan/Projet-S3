<?php
require_once('includes/autoload.inc.php');


if(isset($_REQUEST['idLan'])&&is_numeric($_REQUEST['idLan'])){
$connecte = Member::isConnected();
  try{
    $lan= Lan::createFromId($_REQUEST['idLan']);

    $page = new GeekOnLanWebpage("GeekOnLan -".$lan->getLanName()." - Liste des tournois");
    $page->appendCssUrl("style/regular/listeTournois.css", "screen and (min-width: 680px");
    $page->appendJsUrl("js/listeTournois.js");
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
              <div id="description"><h1>Description du Tournoi</h1></div>
              <table>
              	<tr>
              		<th>Nom</th>
              		<th>Date et heure prévu</th>
              		<th>Type Elimination</th>
              		<th>Nombre d équipes Max</th>
                  <th>Nombre de personnes par equipes</th>
                  <th>Nombre d équipes inscrites</th>
              	</tr>
HTML
      );


        foreach ($tournois as $tournoi){
          $page->appendContent("<tr>");
          $page->appendContent("  <td>".$tournoi->getNomTournoi()."</td>");
          $page->appendContent("  <td>".$tournoi->getDateHeurePrevu()."</td>");
          $page->appendContent("  <td>".$tournoi->getTpElimination()."</td>");
          $page->appendContent("  <td>".$tournoi->getNbPersMaxParEquipe()."</td>");
          $page->appendContent("  <td>".$tournoi->getNbEquipeMax()."</td>");
          $page->appendContent("  <td>".sizeof($tournoi->getEquipe())."</td>");

          if($connecte){
            $page->appendContent('<td>');
            $page->appendContent('  <a href="rejoindreTournoi.php?idLan='.$_REQUEST['idLan'].'&idTournoi='.$tournoi->getIdTournoi().'" class = "bouton">Participer</a>');
            $page->appendContent('  <a href="" class = "bouton" onClick="showDetails('.$tournoi->getIdTournoi().')">Details</a>');
            $page->appendContent('</td>');

          }

        }
      $page->appendContent("</table>");
    }

    echo $page->toHTML();
  }
  catch(Exception $e){
    header('Location: message.php?message=Lan inexistante');
  }
}
else{
  header('Location: message.php?message=Un probleme est survenu');
}
