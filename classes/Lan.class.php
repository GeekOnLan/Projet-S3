<?php

require_once(projectPath . "includes/requestUtils.inc.php");
 
class Lan {

	private $idLAN = null;
	private $idMembre = null;
	private $nomLAN = null;
	private $descriptionLAN = null;
	private $dateLAN = null;
	private $idLieu = null;
	private $adresse = null;
	private $estOuverte = null;

	/**
	 * On empêche l'instanciation d'une Lan
	 */
	private function __construct() {}

	/**
	 * Retourne l'identifiant de la Lan
	 * @return int ID de la lan
	 */
	public function getId() {
		return $this->idLAN;
	}

	/**
	 * Retourn l'identifiant du membre propriétaire de la Lan
	 * @return int ID du créateur
	 */
	public function getIdMember() {
		return $this->idMembre;
	}

	/**
	 * Retourne le nom de la Lan
	 * @return string Le nom de la Lan
	 */
	public function getLanName() {
		return $this->nomLAN;
	}

	/**
	 * Retourne la description de la Lan
	 * @return string La description de la Lan
	 */
	public function getLanDescription() {
		return $this->descriptionLAN;
	}

	/**
	 * Retourne la date de la Lan au format JJ/MM/AAAA
	 * @return string La date de la Lan
	 */
	public function getLanDate() {
		$dat = substr($this->dateLAN,0,10);
		$res = substr($dat,8,2);
		$res.="/";
		$res.=substr($dat,5,2);
		$res.="/";
		$res.=substr($dat,0,4);
		return $res;
	}

	/**
	 * Retourne l'adresse ou se déroulera la Lan
	 * @return string L'adresse de la Lan
	 */
	public function getAdresse() {
		return $this->adresse;
	}

	/**
	 * Retourne le lieu ou se déroulera la Lan
	 * @return Lieu Le lieux de la Lan
	 */
	public function getLieu() {
		return Lieu::createFromId($this->idLieu);
	}

	/**
	 * Retourne l'état de la Lan
	 * @return bool false si la Lan est fermée, true sinon
	 */
	public function isOpen() {
		return $this->estOuverte;
	}

	// TODO commente moi ça je sais pas trop ce qu'elle fait
	public function update($nom,$date,$desc,$lieu,$adress) {
		$idLieu = selectRequest(array("nom" => $lieu), array(PDO::FETCH_ASSOC => null), "idLieu", "Lieu", "nomVille = :nom")[0]['idLieu'];
		
		updateRequest(array("idLan" => $this->idLAN, "nom" => $nom, "date" => $date, "desc" => $desc, "idLieu" => $idLieu, "adresse" => $adress),
			"LAN",
			"nomLAN = :nom, dateLAN = STR_TO_DATE(:date, '%d/%m/%Y'), descriptionLAN = :desc, idLieu = :idLieu, adresse = :adresse",
			"idLAN = :idLan");
	}

	/**
	 * Retourne l'instance d'une Lan à partir de son identifiant
	 *
	 * @param int $id de la lan
	 *
	 * @return Lan
	 * @throws Exception Si la lan n'existe pas
	 */
	public static function createFromId($id) {
		$res = selectRequest(array("id" => $id), array(PDO::FETCH_CLASS => 'Lan'), "*", "LAN", "idLan = :id");

		if(isset($res[0]))
			return $res[0];
		else
			throw new Exception("Aucune Lan trouvée");
	}

	/**
	 * Retourne l'instance d'une Lan à partir de son nom
	 *
	 * @param string $nom de la lan
	 *
	 * @return Lan
	 * @throws Exception Si la lan n'existe pas
	 */
	public static function createFromName($nom) {
		$res = selectRequest(array("nomLan" => $nom), array(PDO::FETCH_CLASS => 'Lan'), "*", "LAN", "nomLan = :nomLan");

		if(isset($res[0]))
			return $res[0];
		else
			throw new Exception("Aucune Lan trouvée");
	}

	// TODO idem que getTournoi : rien à foutre ici et omg faites passez un tableau au lieu d'une méga liste de paramètre
	public function addTournoi($idJeu,$nom,$type,$nbEquipeMax,$nbPersMaxParEquipe,$datePrevu = null,$description = '') {
		if($description == ''){
			$description = "Tounoi crée par " . Member::getInstance()->getPseudo();
		}
		
		$pdo = MyPDO::getInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT count(idTournoi) FROM Tournoi WHERE idLAN = :idLAN ;
SQL
);
		$stmt -> execute(array(':idLAN' => $this->idLAN)) ;
		$nbTournoi=$stmt-> fetch()['count(idTournoi)'];

		$bigmama = array("idLan"=>$this->idLAN,"idTournoi"=>$nbTournoi,"idJeu"=>$idJeu,"nomTournoi"=>$nom,"tpElimination"=>$type,"dateHeurePrevu"=>$datePrevu,"descriptionTournoi"=>$description,"nbEquipeMax"=>$nbEquipeMax,"nbPersMaxParEquipe"=>$nbPersMaxParEquipe);
		insertRequest($bigmama, "Tournoi(idLAN, idTournoi, idJeu, nomTournoi, tpElimination, dateHeurePrevu, descriptionTournoi, nbEquipeMax,nbPersMaxParEquipe)",
			"(:idLan,:idTournoi, :idJeu, :nomTournoi, :tpElimination, STR_TO_DATE(:dateHeurePrevu, '%d/%m/%Y %H:%i'), :descriptionTournoi, :nbEquipeMax, :nbPersMaxParEquipe)");
		
	}

	// TODO rien à foutre dans Lan cette méthode
	public function getTournoi() {
		return selectRequest(array("idLan" => $this->idLAN), array(PDO::FETCH_CLASS => 'Tournoi'), "*", "Tournoi", "idLan = :idLan");
	}

	/**
	 * Affiche la Lan sous la forme d'un tableau
	 * @return string L'affichage
	 */
	public function __toString() {
		return <<<HTML
   <tr>
   		<td>{$this->getLanName()}</td>
   		<td>{$this->getLanDate()}</td>
   		<td>{$this->getLieu()->getNomSimple()}</td>
		<td>{$this->getLanDescription()}</td>
	</tr>
HTML;
	}

	/**
	 * Retourne les Lans qui se déroulerons dans moins d'un mois
	 * @return Lan[] La liste des Lans
	 */
	public static function getRecentLan() {
		return selectRequest(array(), array(PDO::FETCH_CLASS => "Lan"), "*", "LAN", "dateLAN BETWEEN CURDATE() AND ADDDATE(CURDATE(),31) AND estOuverte = 1", "ORDER BY 6");
	}

	/**
	 * Retourne le chemin de l'image associée à la Lan
	 * @return string Chemin de l'image
	 */
	public function getLanPicture(){
	$res = selectRequest(array("idLan" => $this->idLAN), array(PDO::FETCH_ASSOC => null), "imageJeu", "Jeu j, Tournoi t", "t.idLan = :idLan AND t.idTournoi = 0  AND t.idJeu = j.idJeu");
	if(isset($res[0]) && isset($res[0]['imageJeu'])) return $res[0]['imageJeu'];
	else return null;	
	 }

	/**
	 * Supprime la Lan
	 */
	public function delete() {
		$tournois = $this->getTournoi();
		foreach ($tournois as $tournoi){
			$message = "La LAN : ".$this->getLanName()." a été supprimer";
			$tournoi->delete($message);
		}
		deleteRequest(array("id" => $this->idLAN), "LAN", "idLAN = :id");
	}
}
