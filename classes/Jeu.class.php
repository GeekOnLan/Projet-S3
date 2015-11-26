<?php

require_once('includes/myPDO.inc.php');

class Jeu{

    private $idJeu = null;

    private $nomJeu = null;

    private $descriptionJeu = null;

    private $estGratuit = null;

    public function getIdJeu(){
        return $this->idJeu;
    }

    public function getNomJeu(){
        return $this->nomJeu;
    }

    public function getDescriptionJeu(){
        return $this->descriptionJeu;
    }

    public function getEstGratuit(){
        return $this->estGratuit;
    }

    public static function createFromId($id){
        $pdo = MyPDO::GetInstance();
        $stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Jeu
			WHERE idJeu = :id;
SQL
        );
        $stmt->execute(array("id"=>$id));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $jeu = $stmt->fetch();
        if($jeu!==false)
            return $jeu;
        else
            throw new Exception('Ce jeu n\'existe pas');
    }

    public static function createFromName($nomJeu){
        $pdo = MyPDO::GetInstance();
        $stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Jeu
			WHERE nomJeu = :nomJeu;
SQL
        );
        $stmt->execute(array("nomJeu"=>$nomJeu));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $jeu = $stmt->fetch();
        if($jeu!==false)
            return $jeu;
        else
            throw new Exception('Ce jeu n\'existe pas');
    }

    public static function addJeu($nom,$description,$gratuit,$image){
        if($description=='')
            $description="Tounoi crée par ".Member::getInstance()->getPseudo();
        $pdo = MyPDO::GetInstance();
        $stmt = $pdo->prepare(<<<SQL
			INSERT INTO `Jeu`(`nomJeu`, `descriptionJeu`,`estGratuit`,`imageJeu`)
			VALUES (:nomJeu,:descriptionJeu,:estGratuit,:imageJeu);
SQL
        );
        $stmt->execute(array("nomJeu"=>$nom,"descriptionJeu"=>$description,"estGratuit"=>$gratuit,"imageJeu"=>$image));
    }
}