<?php

//a inclure si le fichier php ne doit pas etre accessible par l'utilisateur

if(!isset($_SERVER['HTTP_REFERER']))
    header('Location: ../index.php');
