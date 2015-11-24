<?php

require_once('includes/myPDO.inc.php');

class Lieu{
	
	private $idLieu = null;
	
	private $nomVille = null;
	
	private $arrondissement = null;
	
	private $codePostal = null;
	
	private $nomSimple = null;

	private $departement = null;

	private $slug = null;

	private $canton = null;

	public function getNomSimple(){
		return $this->nomSimple;
	}

	public function getNomVille(){
		return $this->nomVille;
	}

	public function getArrondissement(){
		return $this->arrondissement;
	}

	public function getCodePostal(){
		return $this->codePostal;
	}

	public function getIdLieu(){
		return $this->idLieu;
	}

	public function getCanton(){
		return $this->canton;
	}

	public function getSlug(){
		return $this->slug;
	}

	public function getDepartement(){
		return $this->departement;
	}

	public  function __construct(){}

	public static function createFromId($id){
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Lieu
			WHERE idLieu = :id;
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
}