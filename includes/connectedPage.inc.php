<?php

/**
 * verifie si l'utilisateurd est connecter, si il ne les pas la page et rediriger vers l'index
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/Projet-S3/includes/autoload.inc.php');
	if(!Member::isConnected())
		header('Location: index.php' . SID);