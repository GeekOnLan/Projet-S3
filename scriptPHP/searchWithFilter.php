<?php
require_once('../includes/utility.inc.php');
require_once('../includes/autoload.inc.php');

// ===== Script principal ===== //



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


