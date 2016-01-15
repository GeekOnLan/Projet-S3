<?php
require_once('includes/autoload.inc.php');


if(isset($_REQUEST['idLan'])&&is_numeric($_REQUEST['idLan'])){
$connecte = Member::isConnected();
	$lan=null;
  try{
    $lan= Lan::createFromId($_REQUEST['idLan']);
  }catch(Exception $e){
    	header('Location: message.php?message=Lan inexistante');
  }
    $page = new GeekOnLanWebpage("GeekOnLan -".$lan->getLanName()." - Liste des tournois");
    $page->appendCssUrl("style/regular/listeTournois.css", "screen and (min-width: 680px");
    $page->appendJsUrl("js/listeTournois.js");
    $page->appendJsUrl("js/request.js");

    $tournois=$lan->getTournoi();

    if(sizeof($tournois)==0){
        $page->appendContent(<<<HTML
              <table id="{$_REQUEST['idLan']}">
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
              <table id="{$_REQUEST['idLan']}">
              	<tr >
              		<th>Nom</th>
                  <th>Jeu</th>
              		<th>Date et heure prévu</th>
              		<th>Type Elimination</th>
              		<th>Nombre d équipes Max</th>
                  <th>Nombre de personnes par equipes</th>
                  <th>Nombre d équipes inscrites</th>
              	</tr>
HTML
      );
      
      
      $pdo = MyPDO::getInstance();
      $stmt = $pdo->prepare(<<<SQL
			SELECT idMembre
			FROM Composer
			WHERE idEquipe=(
    			SELECT idEquipe
    			FROM Participer
    			WHERE idLAN=:idLan
    			AND idTournoi=:idTournoi
    		);
SQL
      );


        foreach ($tournois as $tournoi){
          $jeu = $tournoi->getJeu();
          $page->appendContent('  <tr id="'.$tournoi->getIdTournoi().'">');
          $page->appendContent("  <td>".$tournoi->getNomTournoi()."</td>");
          $page->appendContent("  <td>".$jeu[1]."</td>");
          $page->appendContent("  <td>".$tournoi->getDateHeurePrevu()."</td>");
          $page->appendContent("  <td>".$tournoi->getTpElimination()."</td>");
          $page->appendContent("  <td>".$tournoi->getNbEquipeMax()."</td>");
          $page->appendContent("  <td>".$tournoi->getNbPersMaxParEquipe()."</td>");
          $page->appendContent("  <td>".sizeof($tournoi->getEquipe())."</td>");
          $page->appendContent('<td>');
          $page->appendContent('  <button class="details">Details</button>');
          try{
          	$stmt->execute(array("idLan" => $_GET['idLan'],"idTournoi" => $tournoi->getIdTournoi()));
          }
          catch(Exception $e){
          	//header('Location: message.php?message=Un probleme est survenu');
          	var_dump($e);
          }
          $bool=TRUE;
          $res=$stmt->fetchAll();
          if(sizeof($res)!=0)	
	          foreach ($res[0] as $membre)
	          	if($membre==Member::getInstance()->getId())
	          		$bool=FALSE;    
	          	       
	      if($connecte){
	       	if($bool)
	           	$page->appendContent('  <a href="rejoindreTournoi.php?idLan='.$_REQUEST['idLan'].'&idTournoi='.$tournoi->getIdTournoi().'" class = "bouton">Participer</a>');
	       	else{
	          	$page->appendContent('  <a href="" class = "bouton">Gérer</a>');
	       	}
	      }
          $page->appendContent('</td>');
          $page->appendContent('</tr>');
        }
      $page->appendContent("</table>");
    }

    echo $page->toHTML();
}
else{
  header('Location: message.php?message=Un probleme est survenu');
}
