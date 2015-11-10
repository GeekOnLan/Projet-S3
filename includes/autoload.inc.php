<?php

/**
 * importe automatiquement les classes inconnues si elles existent
 * @param string $name le nom de la classe
 * @throws Exception
 */
function __autoload($name){
	$fichier = $_SERVER['DOCUMENT_ROOT'].'/classes/'.$name.'.class.php';
    if(file_exists($fichier))
        require_once($fichier);
    else throw new Exception($fichier." introuvable");
}
