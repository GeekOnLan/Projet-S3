<?php

require_once('includes/myPDO.inc.php');

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
        return $this->idMembre;
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
     * retourne le pseudo
     */
    public function getPseudo(){
    	return $this->pseudo;
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
     * @param $crypt String pseudo et mot de passe crypter du membre
     * @return Member instance du membre
     * @throws Exception si le pseudo ou mot de passe est invalide
     */
    public static function createFromAuth($crypt){
        self::startSession();
        $pdo = MyPDO::GetInstance();
        $stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Membre
			WHERE SHA1(concat(SHA1(pseudo), :challenge, password))=:crypt
				AND estValide = 0;
SQL
        );
        $stmt->execute(array("challenge"=>$_SESSION['challenge'], "crypt" => $crypt));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $member = $stmt->fetch();
        if($member!==false){
            self::challenge();
            return $member;
        }
        else{

            $pdo = MyPDO::GetInstance();
            $stmt = $pdo->prepare(<<<SQL
				SELECT *
				FROM Membre
				WHERE SHA1(concat(SHA1(pseudo), :challenge, password))=:crypt
					AND estValide = 0;
SQL
            );
            $stmt->execute(array("challenge"=>$_SESSION['challenge'], "crypt" => $crypt));
            $stmt->setFetchMode(PDO::FETCH_CLASS ,__CLASS__);
            $member = $stmt->fetch();
            if($member!==false)
                throw new Exception('Vous n\'avez pas valider votre adresse mail');
            else
                throw new Exception('Pseudo ou mot de passe invalide');
        }
    }

    /**
     * ajoute un membre dans la BD
     * @param $pseudo pseudo du membre
     * @param $mail mail du membre
     * @param $password mot de passe du membre
     * @param $fN nom du membre
     * @param $lN prenom du membre
     * @param $bD anniversaire du membre
     */
    public static function createMember($pseudo,$mail,$password,$fN,$lN,$bD){
        self::startSession();
        $pdo = MyPDO::GetInstance();
        $stmt = $pdo->prepare(<<<SQL
        INSERT INTO `Membre`(`nom`, `prenom`, `pseudo`, `mail`, `dateNais`, `password`)
        VALUES (:ln,:fn,:pseudo,:mail,STR_TO_DATE(:birthday, '%d/%m/%Y'),:password)
SQL
        );
        $stmt->execute(array("ln" => $lN, "fn" => $fN, "pseudo" => $pseudo, "password" => $password, "mail" => $mail, "birthday" => $bD));
    }

    /**
     * demmare une session si celle si ne les pas
     * @throws Exception si une erreur de lancement survient
     */
    private static function startSession(){
        if(session_status()==PHP_SESSION_NONE)
            session_start();
    }

    /**
     * indique si le l'utilisateur et connecter
     * @return bool
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
     */
    public function saveIntoSession(){
        self::startSession();
        $_SESSION['Member']=$this;
    }

    /**
     * deconnect le membre
     */
    public static function disconnect(){
        self::startSession();
        $_SESSION['Member']=null;
    }

    /**
     * renvoit l'instance du membre stocker dans la session
     * @return Member
     */
    public static function getInstance(){
        self::startSession();
        if(self::isConnected())
            return $_SESSION['Member'];
        else
            return null;
    }

    /**
     * cree un challenge pour crypter la connexion
     * @return string challenge pour la cennexion
     */
    public static function challenge(){
        $res = '';
        $it = rand(65,90);
        for($i=0;$i<$it;$i++){
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
        $_SESSION['challenge'] = $res;
        return $res;
    }

    /**
     * retour un tableau d'instance de lan cree par l'utilisateur
     * @return array tableau de lan
     */
    public function getLAN(){
    	$pdo = MyPDO::GetInstance();
    	$stmt = $pdo->prepare(<<<SQL
			SELECT idLAN
			FROM LAN
			WHERE idMembre = :idMembre;
SQL
    	);
    	$stmt->execute(array("idMembre"=>$this->getId()));
        $tab = array();
        foreach($stmt as $res){
            array_push ($tab,Lan::createFromId($res['idLAN']));
        }
    	return $tab;
    }
}