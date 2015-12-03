<?php

require_once('../includes/utility.inc.php');
require_once('../classes/ResquestBuilder.class.php');

// ===== Script principal ===== //

$test = new RequestBuilder();
$test->appendJoinTables("Table", "t1", "Table", "t2", "ouech");

// =====   Fin du script  ===== //

/**
 * Retourne la liste des filtres utilisés par l'utilisateur
 *
 * @return array la liste des filtres utilisés
 */
function getUsedFilters() {
	$filtersList = array("name", "region", "departement", "ville", "gratuit", "equipe", "solo");
	$filtersUsed = array();

	foreach($filtersList as $filter) {
		if(verify($_GET, $filter)) 
			$filtersUsed[] = $filter;
	}

	return $filtersUsed;
}
