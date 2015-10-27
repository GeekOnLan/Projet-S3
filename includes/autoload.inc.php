<?php

/**
 * importe automatiquement les classes inconnues si elles existent
 * @param string $name
 */
function __autoload($name) {
    include('classes/' . strtolower($name) . '.class.php');
}