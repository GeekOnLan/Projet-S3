<?php

class Equipe {
	
	private $idEquipe = null;
	
	private $nomEquipe = null;
	
	private $descriptionEquipe = null;
	
	private $inscriptionOuverte = null;
	
	public function getIdEquipe() {
		return $this->idEquipe;
	}
	public function getNomEquipe() {
		return $this->nomEquipe;
	}
	public function getDescriptionEquipe() {
		return $this->descriptionEquipe;
	}
	public function getInscriptionOuverte() {
		return $this->inscriptionOuverte;
	}
	
	public static function createFromId($id){
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Equipe
			WHERE idEquipe = :id;
SQL
		);
		$stmt->execute(array("id"=>$id));
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		$equipe = $stmt->fetch();
		if($equipe!==false)
			return $equipe;
		else
			throw new Exception('cette equipe n\'existe pas');
	}
	
	public function getMembre(){
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Composer
			WHERE idEquipe = :id;
SQL
		);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Membre');
		$stmt->execute(array("id"=>$this->idEquipe));
		return $stmt->fetchAll();
	}
}