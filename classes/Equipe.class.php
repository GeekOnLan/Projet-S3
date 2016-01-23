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
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Membre
			WHERE idMembre IN (SELECT idMembre
			FROM Composer
			WHERE idEquipe = :id);
SQL
		);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Member');
		$stmt->execute(array("id"=>$this->idEquipe));
		return $stmt->fetchAll();
	}

	public function isFull(){
		if($this->inscriptionOuverte==0)
			return true;

		$nbPer = sizeOf($this->getMembre());
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT idLAN, idTournoi
			FROM Participer
			WHERE idEquipe = :idEquipe;
SQL
		);
		$stmt->execute(array("idEquipe"=>$this->idEquipe));
		$res = $stmt->fetchAll();
		$idLan = $res[0]['idLAN'];
		$idTournoi = $res[0]['idTournoi'];
		$max = Tournoi::createFromId($idLan,$idTournoi )->getNbPersMaxParEquipe();

		return ($nbPer>=$max);
	}

	/**
	 * Retourne le createur du tournoi
	 * @return Member
	 */
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

	/**
	 * ajoute un membre a l'equipe
	 *
	 * @param $idMembre id du membre a ajouter a l'equipe
	 */
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

		$message = "Le membre '".$membre->getPseudo()."' a rejoind votre equipe : ".$this->getNomEquipe();
		$createur -> sendNotif("nouveau membre",$message);
	}

	/**
	 * envoie une notification au membre de l'equipe
	 *
	 * @param $objet objet de la notif a envoyer
	 * @param $message memssage a envoyer
	 */
	public function send($objet, $message){
		$membres = $this->getMembre();
		foreach ($membres as $membre){
			$membre->sendNotif($objet, $message);
		}
	}
	
	/**
	 * Supprime l'equipe
	 */
	public function delete($message){
		$this->send("Annulation", $message);
		deleteRequest(array("idEquipe" => $this->idEquipe), "Equipe", "idEquipe = :idEquipe");
	}

	public function removeMember($idMembre,$message){
		$this->send("Annulation", $message);
		deleteRequest(array("idEquipe" => $this->idEquipe,"idMembre" => $idMembre), "Composer", "idEquipe = :idEquipe AND idMembre = :idMembre");
	}
}