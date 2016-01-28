<?php

require_once(projectPath . "includes/myPDO.inc.php");

/**
 * Fonction utilitaire qui réalise une requête SELECT
 *
 * @param array  $params               - Paramètres à bind sous forme d'un tableau : 'clé' => valeur
 * @param array  $fetch                - Type de fetch sous forme d'un tableau : typedefetch(Ex : PDO::FETCH_CLASS) => valeur (uniquement dans le cas du FETCH_CLASS)
 * @param string $select               - Contenu du SELECT (sans inclure le mot clé SELECT)
 * @param string $from                 - Contenu du FROM (sans inclure le mot clé FROM)
 * @param string $where                - Contenu du WHERE (sans inclure le mot clé WHERE)
 * @param string $extraOptions         - Contenu extra. Ex : ORDER BY ..., LIMIT ... (inclure les mots clé)
 *
 * @return array Le résultat de la requête
 * @throws Exception Si une erreur s'est produite
 */
function selectRequest($params, $fetch, $select, $from, $where, $extraOptions = "") {
    $pdo = MyPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
			SELECT $select
			FROM $from
			WHERE $where
			$extraOptions ;
SQL
    );

    // On détermine le mode de Fetch
    if(array_key_exists(PDO::FETCH_CLASS, $fetch))
        $stmt->setFetchMode(PDO::FETCH_CLASS, $fetch[PDO::FETCH_CLASS]);
    else
        $stmt->setFetchMode(array_keys($fetch)[0]);

    // On ajoute tout les paramètres de la requête
    foreach($params as $key => $value) {
        $type = PDO::PARAM_STR;
        if(gettype($value) == "integer")
            $type = PDO::PARAM_INT;

        $stmt->bindValue(":" . $key, $value, $type);
    }

    $stmt->execute();

    if(($res = $stmt->fetchAll()) !== false)
        return $res;
    else
        throw new Exception("Une erreur s'est produite pendant la requête");
}

/**
 * Fonction utilitaire qui réalise une requête DELETE
 *
 * @param array $params    - Paramètres à bind sous forme d'un tableau : 'clé' => valeur
 * @param string $delete   - Contenu du DELETE FROM (sans inclure les mots clés DELETE FROM)
 * @param string $where    - Contenu du WHERE (sans inclure le mot clé WHERE)
 */
function deleteRequest($params, $delete, $where) {
    $pdo = MyPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
        DELETE FROM $delete
        WHERE $where ;
SQL
);
    // On ajoute tout les paramètres de la requête
    foreach($params as $key => $value) {
        $type = PDO::PARAM_STR;
        if(gettype($value) == "integer")
            $type = PDO::PARAM_INT;

        $stmt->bindValue(":" . $key, $value, $type);
    }

    $stmt->execute();
}

/**
 * Fonction utilitaire qui réalise une requête INSERT
 *
 * @param array $params  - Paramètres à bind sous forme d'un tableau : 'clé' => valeur
 * @param string $insert - Contenu du INSERT INTO (sans inclure les mots clés INSERT INTO)
 * @param string $values - Contenu du VALUES (sans inclure le mot clé VALUES)
 */
function insertRequest($params, $insert, $values) {
    $pdo = MyPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
        INSERT INTO $insert
        VALUES $values ;
SQL
);
    // On ajoute tout les paramètres de la requête
    foreach($params as $key => $value) {
        $type = PDO::PARAM_STR;
        if(gettype($value) == "integer")
            $type = PDO::PARAM_INT;

        $stmt->bindValue(":" . $key, $value, $type);
    }

    $stmt->execute();
}

/**
 * Fonction utilitaire qui réalise une requête UPDATE
 *
 * @param array $params    - Paramètres à bind sous forme d'un tableau : 'clé' => valeur
 * @param string $update   - Contenu du UPDATE (sans inclure le mot clé UPDATE)
 * @param string $set      - Contenu du SET (sans inclure le mot clé SET)
 * @param string $where    - Contenu du WHERE (sans inclure le mot clé WHERE)
 */
function updateRequest($params, $update, $set, $where) {
    $pdo = MyPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
        UPDATE $update
        SET $set
        WHERE $where ;
SQL
);
    // On ajoute tout les paramètres de la requête
    foreach($params as $key => $value) {
        $type = PDO::PARAM_STR;
        if(gettype($value) == "integer")
            $type = PDO::PARAM_INT;

        $stmt->bindValue(":" . $key, $value, $type);
    }
    $stmt->execute();
}