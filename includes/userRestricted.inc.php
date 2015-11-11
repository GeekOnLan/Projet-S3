<?php

//a inclure si le fichier php ne doit pas etre accessible par l'utilisateur mais par du php ou de l'ajax

if(!isset($_SERVER['HTTP_REFERER']))
    header('Location: ../index.php');
