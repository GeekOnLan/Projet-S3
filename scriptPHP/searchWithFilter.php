<?php

require_once('../includes/utility.inc.php');
require_once("../includes/myPDO.inc.php");
require_once('../classes/MyPDO.class.php');
require_once('../classes/Lan.class.php');

// ===== Script principal ===== //

$filters = getUsedFilters();

$offset = 0;
if(verify($_GET, "page") && $_GET["page"] > 0)
	$offset = ($_GET["page"] - 1) * 10;

$sql = buildRequest(array_keys($filters));
$res = toJSON(sendRequest($sql, $filters, $offset));
echo $res;

// =====   Fin du script  ===== //

/**
 * Traduit le résultat de la recherche en JSON
 *
 * @param array $res - Le tableau contenant les résultats
 *
 * @return string le code JSON
 */
function toJSON($res) {
	$objets = array();
	foreach($res as $lan) {
		$objets[] = <<<JSON
	{"name": "{$lan->getLanName()}",
	 "date": "{$lan->getLanDate()}",
	 "lieu": "{$lan->getLieux()->getNomSimple()}"}
JSON;
	}

	return "[" . implode(",", $objets) . "]";
}

/**
 * Envoit la requête
 *
 * @param string $sql - Code SQL de la requête
 * @param array $values - tableau contenant les valeurs à remplacer dans la requête
 * @param int $offset - Début de la selection
 *
 * @return array Le résultat de la requête
 */
function sendRequest($sql, $values, $offset) {
	// On supprime les filtres qui n'ont pas besoin d'être placés dans la requête
	// Ex : solo, equipe, gratuit, ...
	$token = array("name" => null, "departement" => null, "ville" => null, "jeu" => null);
	$values = array_intersect_key($values, $token);

	$pdo = MyPDO::getInstance();
	$stmt = $pdo->prepare($sql);

	$stmt->setFetchMode(PDO::FETCH_CLASS, 'Lan');

	$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
	$stmt->bindValue(':size', 10, PDO::PARAM_INT);

	foreach($values as $key => $value)
		$stmt->bindValue(":" . $key, $value, PDO::PARAM_STR);

	$stmt->execute();

	return $stmt->fetchAll();
}

/**
 * Retourne la liste des filtres utilisés par l'utilisateur
 *
 * @return array la liste des filtres utilisés
 */
function getUsedFilters() {
	$filtersList = array("name", "departement", "ville", "gratuit", "equipe", "solo", "jeu");
	$filtersUsed = array();

	foreach($filtersList as $filter) {
		if(isset($_GET[$filter]))
			$filtersUsed[$filter] = $_GET[$filter];
	}

	// On remplace le terme de la recherche pour utiliser LIKE
	if(array_key_exists("name", $filtersUsed))
		$filtersUsed["name"] = "%" . $_GET["name"] . "%";

	// Idem
	if(array_key_exists("ville", $filtersUsed))
		$filtersUsed["ville"] = "%" . $_GET["ville"] . "%";

	// Idem
	if(array_key_exists("jeu", $filtersUsed))
		$filtersUsed["jeu"] = "%" . $_GET["jeu"] . "%";

	return $filtersUsed;
}

/**
 * Construit la requête correspondant à la recherche de l'utilisateur
 *
 * @param $filters		- Liste des filtres utilisés
 * @return string la requête SQL
 */
function buildRequest($filters) {
	$select = "l.nomLAN, l.dateLAN, l.idLieu";
	$from = "LAN l, Lieu li, Tournoi t, Jeu j";
	$where = "l.estOuverte = 1 AND l.idLieu = li.idLieu AND t.idLAN = l.idLAN AND j.idJeu = t.idJeu";

	// Construction de la requête en fonction des filtres utilisés
	foreach($filters as $filter) {
		switch($filter) {
			case "name" :
				$where .= " AND UPPER(l.nomLAN) LIKE :name ";
				break;
			case "departement" :
				$where .= " AND li.departement = :departement ";
				break;
			case "ville" :
				$where .= " AND li.nomSimple LIKE :ville ";
				break;
			case "gratuit" :
				$where .= " AND j.estGratuit = 1 ";
				break;
			case "equipe" :
				$where .= " AND t.nbPersMaxParEquipe > 1";
				break;
			case "solo" :
				$where .= " AND t.nbPersMaxParEquipe = 1";
				break;
			case "jeu" :
				$where .= " AND j.nomJeu LIKE :jeu";
				break;
		}
	}

	return <<<SQL
	SELECT DISTINCT $select
	FROM $from
	WHERE $where
	LIMIT :offset , :size;
SQL;
}
