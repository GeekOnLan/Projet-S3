<?php

class Tournoi{

    private $idLAN = null;
    private $idTournoi = null;
    private $idJeu = null;
    private $nomTournoi = null;
    private $tpElimination = null;
    private $dateHeurePrevu = null;
    private $descriptionTournoi = null;
    private $nbEquipeMax = null;
    private $nbPersMaxParEquipe = null;

    /**
     * Retourne l'identifiant de la Lan du Tournoi
     * @return int L'identifiant
     */
    public function getIdLAN(){
        return $this->idLAN;
    }

    /**
     * Retourne l'identifiant du Tournoi
     * @return int L'identifiant
     */
    public function getIdTournoi(){
        return $this->idTournoi;
    }

    /**
     * Retourne l'instance du jeu du Tournoi
     * @return Jeu Le jeu
     */
    public function getJeu(){
        return Jeu::createFromId($this->idJeu);
    }

    /**
     * Retourne le nom du tournoi
     * @return string Le nom
     */
    public function getNomTournoi(){
        return $this->nomTournoi;
    }

    /**
     * Retourne le type d'élimination du tournoi
     * @return int Le type d'élimination
     */
    public function getTpElimination(){
        return $this->tpElimination;
    }

    /**
     * Retourne la date de début du tournoi sous la forme :
     * JJ/MM/AAAA a HH h MM
     * @return string La date de début
     */
    public function getDateHeurePrevu(){
    	$dat = substr($this->dateHeurePrevu,0);
    	$res = substr($dat,8,2);
    	$res.="/";
    	$res.=substr($dat,5,2);
    	$res.="/";
    	$res.=substr($dat,0,4);
    	$res.=" a ";
    	$res.=substr($dat,11,2);
    	$res.="h";
    	$res.=substr($dat,14,2);
    	return $res;
    }

    /**
     * Retourne la description du tournoi
     * @return string La description
     */
    public function getDescriptionTournoi(){
        return $this->descriptionTournoi;
    }

    /**
     * Retourne le nombre maximum d'équipes autorisées pour ce tournoi
     * @return int Le maximum
     */
    public function getNbEquipeMax(){
        return $this->nbEquipeMax;
    }

    /**
     * Retourne le nombre maximum de personnes par équipe autorisées pour ce tournoi
     * @return int Le maximum
     */
    public function getNbPersMaxParEquipe(){
        return $this->nbPersMaxParEquipe;
    }

    /**
     * Supprime le tournoi
     */
    public function delete(){
        deleteRequest(array("lan" => $this->idLAN, "id" => $this->idTournoi), "Tournoi", "idLan = :lan AND idTournoi = :id");

        // On vérifie s'il reste d'autres tournois pour savoir si l'on doit aussi supprimer la Lan
        $res = selectRequest(array("id" => $this->idLAN), array(PDO::FETCH_ASSOC => null), "*", "Tournoi", "idLan = :id");

        if(count($res) <= 0)
            Lan::createFromId($this->idLAN)->delete();
    }

    /**
     * Retourne l'ensemble des équipes qui participent à ce tournoi
     * @return Equipe[] Les équipes
     */
    public function getEquipe(){
    	// TODO Cette requete vous donnera que l'identifiant de l'équipe
        return selectRequest(array("idLan" => $this->idLAN, "idTournoi" => $this->idTournoi), array(PDO::FETCH_CLASS => "Equipe"),
            "*",
            "Participer",
            "idLan = :idLan AND idTournoi = :idTournoi");
    }

    /**
     * Retourne l'instance d'un tournoi correspondant a un identifiant et une Lan
     *
     * @param int $idLan        - L'identifiant de la Lan
     * @param int $idTournoi    - L'identifiant du Tournoi
     *
     * @return Tournoi
     * @throws Exception Si aucun Tournoi n'est trouvé
     */
    public static function getTournoiFromLAN($idLan,$idTournoi){
        $res = selectRequest(array("idTournoi" => $idTournoi, "idLan" => $idLan), array(PDO::FETCH_CLASS => "Tournoi"),
            "*",
            "Tournoi",
            "idTournoi = :idTournoi AND idLan = :idLan");
        if(isset($res[0]))
            return $res[0];
        else
            throw new Exception("Ce tournoi n'existe pas");
    }
}
