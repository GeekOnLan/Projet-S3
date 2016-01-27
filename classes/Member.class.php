<?php

require_once(projectPath . "includes/requestUtils.inc.php");

class Member {

    private $idMembre = null;
    private $nom = null;
    private $prenom = null;
    private $pseudo = null;
    private $mail = null;
    private $dateNais = null;
    private $estBanni = null;
    private $estValide = null;

    /**
     * Retourne l'identifiant du membre
     * @return int ID du membre
     */
    public function getId() {
        return $this->idMembre;
    }

    /**
     * Retourne le nom de famille du membre
     * @return string Nom de famille
     */
    public function getLastName() {
        return $this->nom;
    }

    /**
     * Retourne le prénom du membre
     * @return string Prénom
     */
    public function getFirstName() {
        return $this->prenom;
    }

    /**
     * Retourne le pseudo du membre
     * @return string Pseudo
     */
    public function getPseudo() {
    	return $this->pseudo;
    }

    /**
     * Retourne l'E-mail du membre
     * @return string L'E-mail
     */
    public function getMail() {
        return $this->mail;
    }

    /**
     * Retourne la date de naissance du membre
     * @return string Date de naissance
     */
    public function getBirthday() {
        return date("d/m/Y", strtotime($this->dateNais));
    }

    /**
     * Indique si le membre est banni
     * @return bool true si le membre est banni, false sinon
     */
    public function isBan() {
        return $this->estBanni;
    }

    /**
     * Indique si le membre a validé son E-mail
     * @return bool true si l'E-mail est valide, false sinon
     */
    public function isValid() {
        return $this->estValide;
    }

    /**
     * On interdit l'instanciation d'un membre
     */
    private function __construct() {}

    /**
     * Retourne une instance du Membre authentifié si les identifiants sont corrects
     *
     * @param $crypt    - Agglomérat de du login, du mot de passe et du challenge
     *
     * @return Member L'instance du membre authentifié
     * @throws Exception Si la connexion échoue
     */
    public static function createFromAuth($crypt) {
        self::startSession();
        $member = selectRequest(array("challenge" => $_SESSION['challenge'], "crypt" => $crypt), array(PDO::FETCH_CLASS => "Member"), "*", "Membre", "SHA1(concat(SHA1(pseudo), :challenge, password)) = :crypt");

        if(isset($member[0])) {
            // TODO rectifier la condition lors de la mise en production
            if(!$member[0]->estValide) {
                self::challenge();
                return $member[0];
            } else {
                throw new Exception("Vous n'avez pas validé votre E-mail");
            }
        } else
            throw new Exception("Pseudo ou mot de passe invalide");
    }

    /**
     * Créée un nouveau membre
     *
     * @param string $pseudo    - Le pseudo du membre
     * @param string $mail      - L'E-mail du membre
     * @param string $password  - Le mot de passe du membre
     * @param string $fN        - Le prénom du membre
     * @param string $lN        - Le nom du membre
     * @param string $bD        - La date de naissance du membre
     */
    public static function createMember($pseudo,$mail,$password,$fN,$lN,$bD) {
        // TODO passer un tableau en paramètre ça serait pas mieux ?
        self::startSession();
        insertRequest(array("ln" => $lN, "fn" => $fN, "pseudo" => $pseudo, "password" => $password, "mail" => $mail, "birthday" => $bD),
            "Membre(nom, prenom, pseudo, mail, dateNais, password)",
            "(:ln, :fn, :pseudo, :mail, STR_TO_DATE(:birthday, '%d/%m/%Y'), :password)");
    }

    public static function createFromId($id) {
        $res = selectRequest(array("id" => $id), array(PDO::FETCH_CLASS => 'Member'), "*", "Membre", "idMembre = :id");

        if(isset($res[0]))
            return $res[0];
        else
            throw new Exception("Aucun membre trouvée");
    }

    public static function createFromPseudo($pseudo) {
        $res = selectRequest(array("pseudo" => $pseudo), array(PDO::FETCH_CLASS => 'Member'), "*", "Membre", "pseudo = :pseudo");

        if(isset($res[0]))
            return $res[0];
        else
            throw new Exception("Aucun membre trouvée");
    }

    /**
     * Démarre la session
     *
     * @throws Exception Si la session n'a pas pu être démarrée
     */
    private static function startSession() {
        if(session_status() == PHP_SESSION_NONE) {
            if(!headers_sent())
                session_start();
            else
                throw new Exception("Erreur de session");
        }
    }

    /**
     * Indique si l'utilisateur est connecté
     *
     * @return bool true s'il est connecté, false sinon
     */
    public static function isConnected() {
        self::startSession();
        if(isset($_SESSION['Member']) && !empty($_SESSION['Member']) && $_SESSION['Member'] != null){
            return true;
        }
        else return false;
    }

    /**
     * Stock l'instance courante dans une variable de session
     */
    public function saveIntoSession() {
        self::startSession();
        $_SESSION['Member'] = $this;
    }

    /**
     * Déconnecte le membre
     */
    public static function disconnect() {
        self::startSession();
        $_SESSION['Member'] = null;
    }

    /**
     * Retourne l'instance du membre sauvegardée dans un variable de session
     * @return Member|null L'instance du membre
     */
    public static function getInstance() {
        self::startSession();
        if(self::isConnected())
            return $_SESSION['Member'];
        else
            return null;
    }

    /**
     * Créée un chaine de caractère aléatoire
     * @return string Challenge pour la connexion
     */
    public static function challenge() {
        // TODO j'hésite à dire qu'elle n'est pas à sa place
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

    // TODO pas à sa place
    public function addLan($name,$date,$adress,$nom,$description = '') {
        if($description == '')
            $description = "LAN créée par " . $this->pseudo;

        // TODO je pense qu'il faudrait vérifier si l'id de lieu existe avant de faire la requête
        $idLieu = selectRequest(array("nom" => $nom), array(PDO::FETCH_ASSOC => null), "idLieu", "Lieu", "nomVille = :nom")[0]['idLieu'];

        insertRequest(array("idMembre" => $this->idMembre, "nomLan" => $name, "descriptionLAN" => $description, "dateLAN" => $date, "adresse" => $adress, "idLieu" => $idLieu),
            "LAN(idMembre, nomLan, descriptionLAN, dateLAN, adresse, idLieu,estOuverte)",
            "(:idMembre, :nomLan, :descriptionLAN, STR_TO_DATE(:dateLAN, '%d/%m/%Y'), :adresse, :idLieu, true)");
    }

    // TODO idem
    public function getLAN() {
        return selectRequest(array("idMembre" => $this->getId()), array(PDO::FETCH_CLASS => "Lan"), "*", "LAN", "idMembre = :idMembre");
    }

    /**
     * Supprime le compte
     */
    public function deleteAccount() {
        deleteRequest(array("id"=>$this->idMembre), "Membre", "idMembre = :id");
        $this->disconnect();
    }
    
    /**
     * Verifie si le membre est deja dans une equipe du tournoi
     */
    public function isInEquipeTournoi($idLAN,$idTournoi){
    	$pdo = MyPDO::getInstance();
    	$stmt = $pdo->prepare(<<<SQL
			SELECT idMembre
			FROM Composer
			WHERE idEquipe IN (
    			SELECT idEquipe
    			FROM Participer
    			WHERE idLAN=:idLan
    			AND idTournoi=:idTournoi
    		)
    		AND idMembre=:idMembre;
SQL
    	);
    	
    	$stmt->execute(array("idLan" => $idLAN,"idTournoi" => $idTournoi,"idMembre" => $this->idMembre));
    	$res=$stmt->fetchAll();
    	$bool = FALSE;
    	if(sizeof($res)>0)
    		$bool = TRUE;
    	return $bool;
    }
    
    public function getLanParticiper(){
    	$pdo = MyPDO::getInstance();
    	$stmt = $pdo->prepare(<<<SQL
			SELECT DISTINCT idLan 
    		FROM Participer
    		WHERE idEquipe IN(
    			SELECT idEquipe 
    			FROM Composer 
    			WHERE idMembre=:id )
SQL
    	);
    	$stmt->execute(array("id" => $this->idMembre));
    	$idLan=$stmt->fetchAll();
    	$lan=array();
    	foreach($idLan as $id){
    		array_push($lan,Lan::createFromId($id['idLan']));
    	}
    	return $lan;
    }
    
    public function getTournoiParticiperFromLan($idLan){
    	$pdo = MyPDO::getInstance();
    	$stmt = $pdo->prepare(<<<SQL
			SELECT DISTINCT idTournoi
    		FROM Participer
    		WHERE idEquipe IN(
    			SELECT idEquipe
    			FROM Composer
    			WHERE idMembre=:idMembre )
            AND idLan=:idLan
SQL
    	);
    	$stmt->execute(array("idMembre" => $this->idMembre,"idLan" => $idLan));
    	$idTournoi=$stmt->fetchAll();
    	$tournoi=array();
    	foreach($idTournoi as $id){
    		array_push($tournoi,Tournoi::getTournoiFromLAN($idLan, $id['idTournoi']));
    	}
    	return $tournoi;
    }
    
    public function getEquipeParticiperFromLanAndTournoi($idLan,$idTournoi){
    	$pdo = MyPDO::getInstance();
    	$stmt = $pdo->prepare(<<<SQL
			SELECT DISTINCT idEquipe
    		FROM Participer
    		WHERE idTournoi=:idTournoi
            AND idLan=:idLan
SQL
    	);
    	$stmt->execute(array("idTournoi" => $idTournoi,"idLan" => $idLan));
    	$idEquipe=$stmt->fetchAll();
    	$equipe=array();
    	foreach($idEquipe as $id){
    		array_push($equipe,Equipe::createFromId($id['idEquipe']));
    	}
    	return $equipe;
    }
    
    public function sendNotif($objet,$message){
    	$pdo = MyPDO::getInstance();

    	$stmt = $pdo->prepare(<<<SQL
			INSERT INTO Notifications (objetNotif, dateNotif, messageNotif) 
    		VALUES (:objet, :date, :message); 
SQL
    	);
    	$stmt->execute(array("objet" => $objet, "date" => date('Y-m-j H-i-s'),"message" => $message));
    	
    	$stmt = $pdo->prepare(<<<SQL
			SELECT MAX(idNotification)
    		FROM Notifications
SQL
    	);
    	$stmt->execute(array("objet" => $objet, "date" => date('Y-m-j H-i-s'),"message" => $message));
    	$idNotif = $stmt->fetch()['MAX(idNotification)'];
    	
    	$stmt = $pdo->prepare(<<<SQL
			INSERT INTO Recevoir (idNotification, idMembre)
    		VALUES (:notif, :membre);
SQL
    	);
    	$stmt->execute(array("notif" => $idNotif, "membre" => $this->idMembre));
    }
    
    public function getNbNotif(){
    	$pdo = MyPDO::getInstance();
    	$stmt = $pdo->prepare(<<<SQL
			SELECT idNotification
    		FROM Recevoir
    		WHERE idMembre=:id;
SQL
    	);
    	$stmt->execute(array("id" => $this->idMembre));
        $res1 = $stmt->fetchAll();

        $stmt = $pdo->prepare(<<<SQL
			SELECT idEquipe
    		FROM Inviter
    		WHERE idMembre=:id;
SQL
        );
        $stmt->execute(array("id" => $this->idMembre));
        $res2 = $stmt->fetchAll();
    	return sizeof($res1)+sizeof($res2);
    }
}
