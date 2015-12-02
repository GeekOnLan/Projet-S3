<?php

class Tournoi{

    private $idLAN = null;

    private $idTournoi = null;

    private $idJeu = null;

    private $nomTournoi = null;

    private $tpElimination = null;

    private $dateHeurPrevu = null;

    private $descriptionTournoi = null;

    private $nbEquipeMax = null;

    private $nbPersMaxParEquipe = null;

    public function getIdLAN(){
        return $this->idLAN;
    }

    public function getIdTournoi(){
        return $this->idTournoi;
    }

    public function getJeu(){
        return Jeu::createFromId($this->idJeu);
    }

    public function getNomTournoi(){
        return $this->nomTournoi;
    }

    public function getTpElimination(){
        return $this->tpElimination;
    }

    public function getDateHeurPrevu(){
        return $this->dateHeurPrevu;
    }

    public function getDescriptionTournoi(){
        return $this->descriptionTournoi;
    }

    public function getNbEquipeMax(){
        return $this->nbEquipeMax;
    }

    public function getNbPersMaxParEquipe(){
        return $this->nbPersMaxParEquipe;
    }

    public static function createFromId($id){
        $pdo = MyPDO::GetInstance();
        $stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Tournoi
			WHERE idTournoi = :id;
SQL
        );
        $stmt->execute(array("id"=>$id));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $lieu = $stmt->fetch();
        if($lieu!==false)
            return $lieu;
        else
            throw new Exception('ce lieux n\'existe pas');
    }

    public function delete(){
        $pdo = MyPDO::GetInstance();
        $stmt = $pdo->prepare(<<<SQL
			DELETE FROM `Tournoi`
			WHERE `idLAN` = :lan
			AND `idTournoi` = :id
SQL
        );
        $stmt->execute(array("lan"=>$this->idLAN,"id"=>$this->idTournoi));
    }
}
