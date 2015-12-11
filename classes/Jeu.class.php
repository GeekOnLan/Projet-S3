<?php

require_once(projectPath . "includes/requestUtils.inc.php");

class Jeu {

    private $idJeu = null;
    private $nomJeu = null;
    private $descriptionJeu = null;
    private $estGratuit = null;

    /**
     * Retourne l'identifiant du jeu
     * @return int Identifiant
     */
    public function getIdJeu(){
        return $this->idJeu;
    }

    /**
     * Retourne le nom du jeu
     * @return string Nom
     */
    public function getNomJeu(){
        return $this->nomJeu;
    }

    /**
     * Retourne la description du jeu
     * @return string Description
     */
    public function getDescriptionJeu(){
        return $this->descriptionJeu;
    }

    /**
     * Indique si le jeu est gratuit
     * @return bool true s'il est gratuit, false sinon
     */
    public function isGratuit(){
        return $this->estGratuit;
    }

    /**
     * Intancie le jeu correspondant à un identifiant
     *
     * @param int $id - L'identifiant
     *
     * @return Jeu L'instance du jeu
     * @throws Exception Si le jeu n'existe pas
     */
    public static function createFromId($id){
        $res = selectRequest(array("id" => $id), array(PDO::FETCH_CLASS, "Jeu"), "*", "Jeu", "idJeu = :id");
        if(isset($res[0]))
            return $res[0];
        else
            throw new Exception("Ce jeu n'existe pas");
    }

    /**
     * Instancie le jeu correspondant à un nom
     *
     * @param string $nomJeu - Le nom du jeu
     *
     * @return Jeu L'instance du jeu
     * @throws Exception Si le jeu n'existe pas
     */
    public static function createFromName($nomJeu){
        $res = selectRequest(array("nomJeu" => $nomJeu), array(PDO::FETCH_CLASS => "Jeu"), "*", "Jeu", "nomJeu = :nomJeu");
        if(isset($res[0]))
            return $res[0];
        else
            throw new Exception("Ce jeu n'existe pas");
    }

    /**
     * Ajoute un jeu
     *
     * @param string $nom          - Le nom du jeu
     * @param string $description  - La description du jeu
     * @param bool $gratuit        - Est-il gratuit ?
     * @param string $image        - Le chemin de l'image associée au jeu
     */
    public static function addJeu($nom,$description,$gratuit,$image){
        if($description == '')
            $description = "Tounoi crée par " . Member::getInstance()->getPseudo();

        insertRequest(array("nomJeu" => $nom, "descriptionJeu" => $description, "estGratuit" => $gratuit, "imageJeu" => $image),
            "Jeu(nomJeu, descriptionJeu, estGratuit,imageJeu)",
            "(:nomJeu, :descriptionJeu, :estGratuit, :imageJeu)");
    }
}