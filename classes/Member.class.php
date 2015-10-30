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

 	

 	private function __construct(){}

 	 /**
	 * Usine pour fabriquer une instance à partir d'un identifiant
	 * Les données sont issues de la base de données
	 * @param int $id identifiant BD du membre à créer
	 * @return Member instance correspondant à $id
	 */
	public static function createFromAuth($pseudo,$mdp) {
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
			$_SESSION['connected']=true;
			
			return $member;
		}
		else{
			throw new Exception("Pseudo/Mot de passe invalide");
		}				  
 	}
 		


 	/*
 	* demmare une session si elle n'est pas deja demmarer
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
 		if(isset($_SESSION['connected'])){
 			return $_SESSION['connected'];
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
 }