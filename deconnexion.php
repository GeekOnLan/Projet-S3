<?php

require_once("includes/autoload.inc.php");

Member::disconnect();
echo "lol";
header('Location: index.php');
exit();