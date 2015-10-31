<?php 

class Member{

	/*
	* id du membre
	*/
	private $idMembre = null;

	/*
	* nom du membre
	*/
	private $nom = null;

	/*
	* prenom du membre
	*/
	private $prenom = null;

	/*
	* pseudo du membre
	*/
	private $pseudo = null;

	/*
	* mail du membre
	*/
	private $mail = null;

	/*
	* dateNais du membre
	*/
	private $dateNais = null;

	// getter

	/*
	* retourne l'id du membre
	*/
	public function getId(){
		return $this->id;
	}

	/*
	* retourne le nom
	*/
	public function getLastName(){
		return $this->nom;
	}

	/*
	* retourne le prenom
	*/
	public function getFirstName(){
		return $this->prenom;
	}

	/*
	* retourne le mail
	*/
	public function getMail(){
		return $this->mail;
	}

	/*
	* retourne la date de naissance
	*/
	public function getBirthday(){
		return $this->dateNais;
	}

	/*
	* pour ne pas cree d`instance de membre sans id
	*/
	private function __construct(){}

	 /**
	 * Usine pour fabriquer une instance à partir d'un pseudo et un mot de passe
	 * Les données sont issues de la base de données
	 */
	public static function createFromAuth($pseudo,$mdp) {
		myPDO::setConfiguration('mysql:host=localhost;dbname=geekOnLAN;charset=utf8', 'root', '');
		$pdo = myPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Member
			WHERE pseudo = ? AND pass = ?;
SQL
		);
		$stmt->execute(array($pseudo,SHA1($mdp)));
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		$member = $stmt->fetch();
		if($member!==false){
			self::startSession();
			return $member;
		}
		else{
			throw new Exception("Pseudo ou mot de passe invalide");
		}		  
	}
		
	/*
	* demarre une session si elle n'est pas deja demarrer
	*/
	private static function startSession(){
		if(session_status()==PHP_SESSION_NONE){
			if(session_status()==PHP_SESSION_ACTIVE)throw new Exception('erreur lancement de session');
			else session_start();
		}
	}

	/*
	* return true si le membre et connecter false sinon
	*/
	public static function isConnected(){
		self::startSession();
		if(isset($_SESSION['Member']) && !empty($_SESSION['Member'])){
			return true;
		}
		else return false;
	}

	/*
	* stock le membre dans la session
	*/
	public function saveIntoSession(){
		self::startSession();
		$_SESSION['Member']=$this;
	}

	public static function disconnect(){
		self::startSession();
		$_SESSION['Member']=null;
	}

	public static function GetInstance(){
		self::startSession();
		if(self::isConnected())
			return $_SESSION['Member'];
		else
			return null;
	}
}