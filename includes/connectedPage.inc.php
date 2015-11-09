<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Projet-S3/includes/autoload.inc.php');
	if(!Member::isConnected())
		header('Location: index.php' . SID);