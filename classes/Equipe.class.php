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
		$res = selectRequest(array("id" => $id), array(PDO::FETCH_CLASS => "Equipe"), "*", "Equipe", "idEquipe = :id");
		if(sizeof($res)==0)
			throw new Exception("Aucune Equipe trouvée");
				
		$res = $res[0];
		if(isset($res))
			return $res;
		else
			throw new Exception("Aucune Equipe trouvée");
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

	public function isFullOfMember(){
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
		if($this->isFromMember()){
			throw new Exception('equipe pleine');
		}

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

		$message = "Le membre '".$membre->getPseudo()."' a rejoind votre equipe '".$this->getNomEquipe()."'";
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

	/**
	 * @param $idMembre id du membre a enlever de l'equipe
	 * @param $message message a lui envoyer
	 */
	public function removeMember($idMembre,$message,$messageOther){
		if($idMembre==$this->getCreateur()->getId())
			new Exception("impossible d'exclure le createur, vous devez supprimer l'equipe entiere !");
		try{
			deleteRequest(array("idEquipe" => $this->idEquipe,"idMembre" => $idMembre), "Composer", "idEquipe = :idEquipe AND idMembre = :idMembre");
			Member::createFromId($idMembre)->sendNotif("Annulation",$message);
			$membres = $this->getMembre();
			foreach ($membres as $membre){
				if($membre->getId()!=$idMembre)
					$membre->sendNotif("Annulation", $messageOther);
			}
		}
		catch(Exception $e){
			throw new Exception($e);
		}
	}

	public function isInEquipe($idMembre){
		foreach($this->getMembre() as $member)
			if($member->getId() == $idMembre)
				return true;
		return false;
	}

	public function isFromLanMember($idMembre){
		$pdo = MyPDO::getInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT idEquipe
			FROM Participer
			WHERE idLAN IN (
				SELECT idLan
				FROM LAN
				WHERE idMembre = :idMembre
			)
SQL
		);
		 $stmt->execute(array("idMembre" => $idMembre));
		 return (!$stmt->fetchAll()==null);
	}

	public function isFromMember($idMembre){
		$pdo = MyPDO::getInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT idEquipe
			FROM Participer
			WHERE idLAN IN (
				SELECT idLan
				FROM Lan
				WHERE idMembre = :idMembre
			)
SQL
		);
		$stmt->execute(array("idMembre" => $idMembre));
		return (!$stmt->fetchAll()==null);
	}

	public function inviteMember($idMembre){
		$pdo = MyPDO::getInstance();
		$stmt = $pdo->prepare(<<<SQL
			INSERT INTO Inviter (idMembre, idEquipe)
    		VALUES (:membre, :equipe);
SQL
		);
		try {
			$stmt->execute(array("membre" => $idMembre, "equipe" => $this->idEquipe));
		}
		catch(Exception $e){
			throw new Exception($e);
		}
	}
}