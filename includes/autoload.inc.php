<?php

/**
 * importe automatiquement les classes inconnues si elles existent
 * @param string $name
 */
function __autoload($name) {
	$fichier = '../classes/'.$name.'.class.php';
    if(file_exists($fichier)) include $fichier;
    else throw new Exception("la classe ".$name." est n'existe pas dans ../classes/");
}