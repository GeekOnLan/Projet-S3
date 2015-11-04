<?php

require_once("includes/utility.inc.php");

if(verify($_GET,'pseudo')){
    $pdo = myPDO::GetInstance();
    $stmt = $pdo->prepare(<<<SQL
                SELECT *
                FROM Membre
                WHERE pseudo = :pseudo;
SQL
    );
    $stmt->execute(array("pseudo" => $_GET['pseudo']));
    $member = $stmt->fetch();
    if($member==false){
        echo "<div id=\"valide\">true</div>";
    }
    else
        echo "<div id=\"valide\">false</div>";
}
else
    echo "<div id=\"valide\"Exception</div>";