<?php 

require_once("includes/autoload.inc.php");
require_once("includes/myPDO.inc.php");

class Member {

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

	/*
	* savoir si le membre est banni
	*/
	private $estBanni = null;

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
	* retourne letat du membre, banni ou non
	*/
	public function getBan(){
		return $this->estBanni;
	}

	/**
	 * pour ne pas cree d'instance de Member
	 */
	private function __construct(){}

	/**
	 * cree une instance de Member
	 * @param $pseudo pseudo du membre
	 * @param $mdp mot de passe du membre
	 * @return Member instance du membre
	 * @throws Exception si le pseudo ou mot de passe est invalide
	 */
	public static function createFromAuth($crypt){
		self::startSession();
		$pdo = MyPDO::GetInstance();
		$stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Membre
			WHERE SHA1(concat(SHA1(pseudo), :salt, password))=:crypt;
SQL
		);
		$stmt->execute(array("salt" => $_SESSION['salt'], "crypt" => $crypt));
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		$member = $stmt->fetch();
		unset($_SESSION['salt']);
		if($member!==false){
			self::startSession();
			return $member;
		}
		else{
			throw new Exception("Pseudo ou mot de passe invalide");
		}		  
	}

	/**
	 * demmare une session si celle si ne les pas
	 * @throws Exception si une erreur de lancement survient
	 */
	private static function startSession(){
		if(session_status()==PHP_SESSION_NONE)
			 session_start();
		elseif(session_status()==PHP_SESSION_DISABLED)
			throw new Exception('erreur lancement de session du a php les session ne sont pas activer');
	}

	/**
	 * indique si le l'utilisateur et connecter
	 * @return bool
	 * @throws Exception
	 */
	public static function isConnected(){
		self::startSession();
		if(isset($_SESSION['Member']) && !empty($_SESSION['Member']) && $_SESSION['Member']!=null){
			return true;
		}
		else return false;
	}

	/**
	 * stock l'instance du membre dans une variable de session
	 * @throws Exception si la session a un probleme de lancement
	 */
	public function saveIntoSession(){
		self::startSession();
		$_SESSION['Member']=$this;
	}

	/**
	 * deconnect le membre
	 * @throws Exception si la session a un probleme de lancement
	 */
	public static function disconnect(){
		self::startSession();
		$_SESSION['Member']=null;
	}

	/**
	 * renvoit l'instance du membre stocker dans la session
	 * @return Membre
	 * @throws Exception si la session a un probleme de lancement
	 */
	public static function GetInstance(){
		self::startSession();
		if(self::isConnected())
			return $_SESSION['Member'];
		else
			return null;
	}

	public static function SaltGrain(){
		$res = '';
		for($i=0;$i<256;$i++){
			$char = rand(0,2);
			switch($char){
				case 0:
					$res.=chr(rand(65,90));
					break;
				case 1:
					$res.=chr(rand(97,122));
					break;
				case 2:
					$res.=chr(rand(48,57));
					break;
			}
		}
		self::startSession();
		$_SESSION['salt'] = $res;
		return $res;
	}
}