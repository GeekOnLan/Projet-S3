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
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Membre');
		$stmt->execute(array("id"=>$this->idEquipe));
		return $stmt->fetchAll();
	}

	public static function createEquipe($idLan,$idTournoi,$nom,$desc="") {
		insertRequest(array("nom" => $nom, "desc" => $desc),
			"equipe(nomEquipe, descriptionEquipe, inscriptionOuverte)",
			"(:nom, :desc, 1)");
		/*insertRequest(array("equipe" => $nom, "lan" => $idLan, "tournoi" => $idTournoi),
			"participer(idEquipe, idLan, idTournoi)",
			"(:equipe, :lan, :tournoi)");*/
	}
}