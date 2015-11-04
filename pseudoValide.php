<?php
echo "<div id=\"valide\">false</div>";
/*
$pdo = myPDO::GetInstance();
$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Membre
			WHERE pseudo = :speudo;
SQL
);
$stmt->execute(array("pseudo" => $_POST['pseudo']);
$member = $stmt->fetch();
if($member==false){
    echo "<div id=\"valide\">true</div>";
}
else
    echo "<div id=\"valide\">false</div>";*/