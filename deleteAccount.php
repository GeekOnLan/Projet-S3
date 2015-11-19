<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');
require_once('includes/utility.inc.php');

if(verify($_SERVER,'HTTP_REFERER')) {
    $member = Member::getInstance();

    try {
        $member->deleteAccount();
        header('Location: index.php');
    }
    catch(Exception $e){
        header('Location: index.php');
    }
}
else
    header('Location: index.php');
