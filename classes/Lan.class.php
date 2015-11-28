<?php

require_once('includes/myPDO.inc.php');
 
class Lan{

	/**
	 * @var null id de la lan
	 */
	private $idLAN = null;

	/**
	 * @var null id de la lan
	 */
	private $idMembre = null;

	/**
	 * @var null nom de la lan
	 */
	private $nomLAN = null;

	/**
	 * @var null description de la lan
	 */
	private $desciptionLAN = null;

	/**
	 * @var null date de la lan
	 */
	private $dateLAN = null;

	/**
	 * @var null id du lieux de la lan
	 */
	private $idLieu = null;

	/**
	 * @var null adresse de la lan
	 */
	private $adresse = null;

	/**
	 * @var null retour true si la lan est ouverte
	 */
	private $estOuverte = null;

	/**
	 * @return id de la lan
	 */
	public function getId(){
		return $this->idLAN;
	}

	/**
	 * @return id du membre
	 */
	public function getIdMember(){
		return $this->idMembre;
	}

	/**
	 * @return le nom de la lan
	 */
	public function getLanName(){
		return $this->nomLAN;
	}

	/**
	 * @return la description de la lan
	 */
	public function getLanDescription(){
		return $this->desciptionLAN;
	}

	/**
	 * @return la date de la lan
	 */
	public function getLanDate(){
		return $this->dateLAN;
	}

	/**
	 * @return l'adresse de la lan
	 */
	public function getAdress(){
		return $this->adresse;
	}

	/**
	 * @return le lieux de la lan
	 */
	public function getLieux(){
		return Lieu::createFromId($this->idLieu);
	}

	/* WALLAH FAUT FAIRE LES SETTERS */
public function setName($nom){}
public function setDate($date){}
public function setDescription($desc){}
public function setLieux($lieux){}
public function setAdress($adress){}
/*--------------------------------------------------------------*/
	/**
	 * @return l'etat de la lan ouverte ou fermer
	 */
	public function isOpen(){
		return $this->estOuverte;
	}

	/**
	 * pour empecher de cree des instance de lan
	 */
	private function __construct(){}

	/**
	 * cree une instance de lan a partir de son id
	 * @param $id id de la lan
	 * @return mixed Lan
	 * @throws Exception si la lan n'existe pas
	 */
	public static function createFromId($id){
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->c(<<<SQL
			SELECT *
			FROM LAN
			WHERE idLAN = ?;
SQL
		);
		$stmt->execute(array($id));
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		$lan = $stmt->fetch();
		if($lan!==false)
			return $lan;
		else
			throw new Exception('cette Lan n\'existe pas');
	}

	public static function getLanFromRange($offset, $limit) {
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM LAN
			LIMIT :fromOffset , :toLimit;
SQL
);
		$stmt->bindValue(':fromOffset', $offset, PDO::PARAM_INT);
		$stmt->bindValue(':toLimit', $limit, PDO::PARAM_INT);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);

		if(($lans = $stmt->fetchAll()) !== false)
			return $lans;
		else
			throw new Exception("En dehors des limites");
	}

	public function addTournoi($idJeu,$nom,$type,$nbEquipeMax,$nbPersMaxParEquipe,$datePrevu = null,$description = ''){
		if($description=='')
			$description="Tounoi crée par ".Member::getInstance()->getPseudo();
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			INSERT INTO `Tournoi`(`idLan`,`idJeu`, `nomTournoi`, `tpElimination`, `dateHeurePrevu`, `descriptionTournoi`, `nbEquipeMax`,`nbPersMaxParEquipe`)
			VALUES (:idLan,:idJeu,:nomTournoi,:tpElimination,STR_TO_DATE(:dateHeurePrevu, '%d/%m/%Y'),:descriptionTournoi,:nbEquipeMax,:nbPersMaxParEquipe);
SQL
		);
		$stmt->execute(array("idLan"=>$this->idLAN,"idJeu"=>$idJeu,"nomTournoi"=>$nom,"tpElimination"=>$type,"dateHeurePrevu"=>$datePrevu,"descriptionTournoi"=>$description,"nbEquipeMax"=>$nbEquipeMax,"nbPersMaxParEquipe"=>$nbPersMaxParEquipe));
	}

	public function getTournoi(){
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Tournoi
			WHERE idLAN = :idLAN;
SQL
		);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Tournoi');
		$stmt->execute(array("idLAN"=>$this->idLAN));
		return $stmt->fetchAll();
	}

	/**
	 * @return string un affichage de la lan en tableau
	 */
	public function toString(){
		$donnees = <<<HTML
   <tr>
   		<td>{$this->getLanName()}</td>
   		<td>{$this->getLanDate()}</td>
   		<td>{$this->getLieux()->getNomSimple()}</td>
   </tr>
HTML;
		return $donnees;
	}

	/*
	 * Permet de récupérer toutes les LANS qui se
	 */
	public static function getLanFrom(){
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT idLAN
            FROM LAN
            WHERE dateLAN BETWEEN CURDATE() AND ADDDATE(CURDATE(),31)
            AND estOuverte = 1;
SQL
		);
		$stmt->execute();
        $res = $stmt->fetchAll();
        return $res;

	}

	/**
	 * Permet de supprimer une LAN
     */
	public function deleteLan(){
		//TODO:Implement
	}
}