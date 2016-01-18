<?php

require_once(projectPath . "includes/requestUtils.inc.php");

class Equipe {
	
	private $idEquipe = null;
	private $nomEquipe = null;
	private $descriptionEquipe = null;
	private $inscriptionOuverte = null;

	/**
	 * Retourne l'identifiant de l'équipe
	 * @return int L'identifiant
	 */
	public function getIdEquipe() {
		return $this->idEquipe;
	}

	/**
	 * Retourne le nom de l'équipe
	 * @return string Le nom
	 */
	public function getNomEquipe() {
		return $this->nomEquipe;
	}

	/**
	 * Retourne la description de l'équipe
	 * @return string La description
	 */
	public function getDescriptionEquipe() {
		return $this->descriptionEquipe;
	}

	/**
	 * Retourne le type d'invitation
	 * @return bool true si l'inscription et ouverte a tous, false sur invitation du chef
	 */
	public function getInscriptionOuverte() {
		return $this->inscriptionOuverte;
	}

	/**
	 * Créé une instance d'Equipe à partir de son
	 * identifiant
	 *
	 * @param int $id - L'identifiant
	 * @return Equipe L'instance créée
	 */
	public static function createFromId($id){
		return selectRequest(array("id" => $id), array(PDO::FETCH_CLASS => "Equipe"), "*", "Equipe", "idEquipe = :id")[0];
	}

	/**
	 * Retourne l'ensemble des membres de l'équipe
	 *
	 * @return Member[] Les membres
	 */
	public function getMembre(){
		// TODO cette requete va remplir que l'attribut id du Membre. C'est bien ce que vous voulez ?
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Composer
			WHERE idEquipe = :id;
SQL
		);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Member');
		$stmt->execute(array("id"=>$this->idEquipe));
		return $stmt->fetchAll();
	}

	public static function createEquipe($idLan,$idTournoi,$nom,$ouverte,$idMembre,$desc="") {
		insertRequest(array("nom" => $nom, "desc" => $desc,"ouvert"=>$ouverte),
			"Equipe(nomEquipe, descriptionEquipe, inscriptionOuverte)",
			"(:nom, :desc, :ouvert)");

		$res = selectRequest(array("nom" => $nom, "desc" => $desc,"ouvert"=>$ouverte),array(PDO::FETCH_ASSOC => null),
			"idEquipe",
			"Equipe",
			"nomEquipe=:nom
			AND descriptionEquipe=:desc
			AND  inscriptionOuverte=:ouvert");
		
		$idEquipe = intval($res[0]['idEquipe']);
		
		insertRequest(array("idEquipe" => $idEquipe, "idLan" => $idLan, "idTournoi" => $idTournoi),
		"Participer(idEquipe,idLan,idTournoi)",
		"(:idEquipe, :idLan, :idTournoi)");
		
		insertRequest(array("idEquipe" => $idEquipe, "idMembre" => $idMembre),
		"Composer(idMembre,idEquipe,role)",
		"(:idMembre, :idEquipe,0)");
	}
	
	public function getCreateur(){
		$pdo = MyPDO::getInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Membre
			WHERE idMembre =(
				SELECT DISTINCT idMembre
	    		FROM Composer
	    		WHERE idEquipe = :idEquipe
				AND role = 0
				);
SQL
		);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Member');
		$stmt->execute(array("idEquipe" => $this->idEquipe));
		return $stmt->fetch();
	}
	
	public function rejoindre($idMembre){
		insertRequest(array("idMembre" => $idMembre, "idEquipe" => $this->idEquipe),
		"Composer(idMembre,idEquipe,role)",
		"(:idMembre, :idEquipe, 1)");
		$createur = $this->getCreateur();
		
		$pdo = MyPDO::getInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Membre
			WHERE idMembre=:id
SQL
		);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Member');
		$stmt->execute(array("id" => $idMembre));
		$membre=$stmt->fetch();

		$message = "Le membre ".$membre->getPseudo()." a rejoind votre equipe : ".$this->getNomEquipe();
		$createur -> sendNotif("nouveau membre",$message);
	}
	
	/**
	 * Supprime l'equipe
	 */
	public function delete($message="Votre équipe a été supprimé"){
		$membres = $this->getMembre();
		foreach ($membres as $membre){
			$membre->sendNotif("Annulation", $message);
		}
		deleteRequest(array("idEquipe" => $this->idEquipe), "Equipe", "idEquipe = :idEquipe");
	}
}