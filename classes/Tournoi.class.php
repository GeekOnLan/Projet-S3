<?php

class Tournoi{

    private $idLAN = null;

    private $idTournoi = null;

    private $idJeu = null;

    private $nomTournoi = null;

    private $tpElimination = null;

    private $dateHeurePrevu = null;

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

    public function getDateHeurePrevu(){
    	$dat = substr($this->dateHeurePrevu,0);
    	$res = substr($dat,8,2);
    	$res.="/";
    	$res.=substr($dat,5,2);
    	$res.="/";
    	$res.=substr($dat,0,4);
    	$res.=" a ";
    	$res.=substr($dat,11,2);
    	$res.="h";
    	$res.=substr($dat,14,2);
    	return $res;
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

        $stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Tournoi
			WHERE idLan = :id;
SQL
        );
        $stmt->execute(array("id"=>$this->idLAN));
        if($stmt->fetch()==null){
            Lan::createFromId($this->idLAN)->delete();
        }
    }
    
    public function getEquipe(){
    	$pdo = MyPDO::GetInstance();
    	$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Participer
			WHERE idLan = :idLan
    		AND idTournoi=:idTournoi;
SQL
    	);
    	$stmt->setFetchMode(PDO::FETCH_CLASS, 'Equipe');
    	$stmt->execute(array("idLan"=>$this->idLAN,"idTournoi"=>$this->idTournoi));
    	return $stmt->fetchAll();
    }
    
    public static function getTournoiFromLAN($idLan,$idTournoi){
    	$pdo = MyPDO::GetInstance();
        $stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Tournoi
			WHERE idTournoi = :idTournoi
        	AND idLan=:idLan;
SQL
        );
        $stmt->execute(array("idTournoi"=>$idTournoi,"idLan"=>$idLan));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $tournoi = $stmt->fetch();
        if($tournoi!==false)
            return $tournoi;
        else
            throw new Exception('ce tournoi n\'existe pas');
    }
}
