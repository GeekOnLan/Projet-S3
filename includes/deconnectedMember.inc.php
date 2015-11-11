<?php

/**
 * verifie si l'utilisateurd est deconnecter, si il ne les pas la page et rediriger vers l'index
 */
require_once('autoload.inc.php');
if(Member::isConnected())
    header('Location: index.php'.SID);