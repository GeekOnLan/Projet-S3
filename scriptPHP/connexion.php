<?php
header('Content-Type: text/xml');
header('Content-Type: application/xml');
require_once("../includes/utility.inc.php");
require_once("../includes/myPDO.inc.php");
require_once("../includes/autoload.inc.php");

$xml = "<?xml version = \"1.0\" encoding=\"UTF-8\"?>";
echo $xml."<response>false</response>";
/*
if(verify($_GET,'crypt')){
    try{
        $member = Member::createFromAuth($_GET['crypt']);
        $member->saveIntoSession();
        echo $xml."<response>True</response>";
    }
    catch (Exception $e){
        echo $xml."<response>false</response>";
    }
}
else
    echo $xml."<response>false</response>";*/