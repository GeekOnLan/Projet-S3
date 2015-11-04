<?php
function verify($champs,$string){
    return (isset($champs[$string])&&!empty($champs[$string]));
}
