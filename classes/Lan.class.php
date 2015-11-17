<?php

require_once('includes/myPDO.inc.php');
 
class Lan{
	
	private $idLAN = null;
	
	private $idMembre = null;
	
	private $nomLAN = null;
	
	private $desciptionLAN = null;
	
	private $dateLAN = null;
	
	private $idLieux = null;
	
	private $adresse = null;
	
	private $estOuverte = null;
	
	public function getId(){
		return $this->$idLAN;
	}
	
	public function getIdMember(){
		return $this->$idMembre;
	}
	
	public function getnLanName(){
		return $this->$nomLAN;
	}
	
	public function getLanDescription(){
		return $this->$desciptionLAN;
	}
	
	public function getLanDate(){
		return $this->$dateLAN;
	}
	
	public function getAdress(){
		return $this->$adresse;
	}
	
	public function getLieux(){
		return $this->$idLieux;
	}
	
	public function isOpen(){
		return $this->$estOuverte;
	}
	
	private function __construct(){}
	
	public static function createFromId($id){
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM LAN
			WHERE idLAN = :id;
SQL
		);
		$stmt->execute(array("id"=>$id));
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		$lan = $stmt->fetch();
		if($lan!==false)
			return $lan;
		else
			throw new Exception('cette Lan n\'existe pas');
	}
	
	public static function addLan($name,$date,$adress,$idLieux,$description = ''){
		if(!Member::isConnected())
			throw new Exception('le membre n\'est pas connecter');
		$member=Member::GetInstance();
		$id=$member->getId();
		
		if($description=='')
			$description="LAN crée par ".$member.getPseudo();
		
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			INSERT INTO `LAN`(`idMembre`, `nomLan`, `descriptionLAN`, `dateLAN`, `adresse`, 'idLieux',`estOuverte`)
			VALUES (:idMembre,:nomLan,:descriptionLAN,:dateLAN,:adresse,:idLieux,false);
SQL
		);
		$stmt->execute(array("idMembre"=>$id,"nomLan"=>$name,"descriptionLAN"=>$description,"dateLAN"=>$date,"adresse"=>$adress,"idLieux"=>$idLieux));
	}
}