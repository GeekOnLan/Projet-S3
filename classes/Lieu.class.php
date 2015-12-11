<?php

require_once(projectPath . "includes/requestUtils.inc.php");

class Lieu {
	
	private $idLieu = null;
	private $nomVille = null;
	private $arrondissement = null;
	private $codePostal = null;
	private $nomSimple = null;
	private $departement = null;
	private $slug = null;
	private $canton = null;

	/**
	 * On interdit l'instanciation d'un lieu
	 */
	private function __construct(){}

	/**
	 * Retourne le nom simple de la ville
	 * @return string Nom simple
	 */
	public function getNomSimple(){
		return $this->nomSimple;
	}

	/**
	 * Retourne le nom de la ville
	 * @return string Nom
	 */
	public function getNomVille(){
		return $this->nomVille;
	}

	/**
	 * Retourne l'arrondissement de la ville
	 * @return string Arrondissement
	 */
	public function getArrondissement(){
		return $this->arrondissement;
	}

	/**
	 * Retourne le code postal de la ville
	 * @return string Code postal
	 */
	public function getCodePostal(){
		return $this->codePostal;
	}

	/**
	 * Retourne l'identifiant unique du lieu
	 * @return int Identifiant
	 */
	public function getIdLieu(){
		return $this->idLieu;
	}

	/**
	 * Retourne le canton de la ville
	 * @return int Canton
	 */
	public function getCanton(){
		return $this->canton;
	}

	/**
	 * Retourne le nom unique de la ville sans caractères spéciaux
	 * @return string Nom
	 */
	public function getSlug(){
		return $this->slug;
	}

	/**
	 * Retourne le numéro de département de la ville
	 * @return int Département
	 */
	public function getDepartement(){
		return $this->departement;
	}

	/**
	 * Instancie un lieu à partir de son identifiant
	 *
	 * @param int $id - L'identifiant
	 *
	 * @return Lieu L'instance du lieu
	 *
	 * @throws Exception Si le lieu n'existe pas
	 */
	public static function createFromId($id) {
		$res = selectRequest(array("id" => $id), array(PDO::FETCH_CLASS => "Lieu"), "*", "Lieu", "idLieu = :id");
		if(isset($res[0]))
			return $res[0];
		else
			throw new Exception("Ce lieux n'existe pas");
	}
}
