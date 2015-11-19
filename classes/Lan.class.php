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
	private $idLieux = null;

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
		return $this->idLieux;
	}

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

	/**
	 * ajoute une lan dans la BD
	 * @param $name nom de la lan
	 * @param $date date de la lan
	 * @param $adress adresse dela lan
	 * @param $idLieux lieux de la lan
	 * @param string $description description de la lan
	 * @throws Exception
	 */
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

	/**
	 * @return string un affichage de la lan en tableau
	 */
	public function toString(){
		$donnees = <<<HTML
<table>
   <tr>
   		<td>{$this->getLanName()}</td>
   	</tr>
   <tr>
  		<td>{$this->getLanDescription()}</td>
   </tr>
   <tr>
   		<td>{$this->getLanDate()}</td>
   	</tr>
   <tr>
   		<td>{$this->getLieux()}</td>
   </tr>
   <tr>
   		<td>{$this->getAdress()}</td>
   </tr>
</table>
HTML;
	return $donnees;
	}
}