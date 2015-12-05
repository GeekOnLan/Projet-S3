<?php

// Mes Lan : Si pas de Lans, pas de tableau vide : message
// Pallalol ?
// Verification PHP pour créerLan
// Champs obligatoire pour créer Lan
// Ajouter un tournoi obligatoire
// Description du tournoi vérification boiteuse
// Détails de Lan fonctionnel (nico ?)

class RequestBuilder {

    private $select;
    private $fromTable;
    private $where;
    private $extraOptions;

    /**
     * Construit un constructeur de requête
     *
     * @param array $select - les champs sélectionnés
     */
    public function __construct($select = array("*")) {
        $this->select = $select;
        $this->fromTable = array();
        $this->where = array();
        $this->extraOptions = array();
    }

    /**
     * Ajoute une table avec un alias
     *
     * @param $table - Nom de la table
     * @param $alias - Alias de la table
     */
    public function appendTable($table, $alias) {
        if(array_search($alias, $this->fromTable))
            throw new InvalidArgumentException("L'alias $alias existe(nt) déjà");

        $this->fromTable[$table] = $alias;
    }

    /**
     * Ajoute deux tables liées par une jointure
     *
     * @param $table1 - Nom de la table 1
     * @param $alias1 - Alias de la table 1
     * @param $table2 - Nom de la table 2
     * @param $alias2 - Alias de la table 2
     * @param $field  - champs de la jointure
     */
    public function appendJoinTables($table1, $alias1, $table2, $alias2, $field) {
        if(array_search($alias1, $this->fromTable) || array_search($alias2, $this->fromTable))
            throw new InvalidArgumentException("L'alias $alias1 ou $alias2 existe(nt) déjà");
        else
            $this->fromTable[] = "$table1 $alias1 INNER JOIN $table2 $alias2 ON $alias1.$field = $alias2.$field";
    }
}
