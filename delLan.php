<?php
require_once('includes/autoload.inc.php');
require_once('includes/connectedMember.inc.php');

if(isset($_REQUEST['idLan'])&&is_numeric($_REQUEST['idLan'])) {
    $member = Member::getInstance();
    $lans = Member::getInstance()->getLAN();
    if ($_REQUEST['idLan'] <= sizeof($lans) - 1) {
        $lan = $lans[$_REQUEST['idLan']];
        try {
            $lan->delete();
            header('Location: index.php');
        } catch (Exception $e) {
            header('Location: index.php');
        }
    }
    else
        header('Location: index.php');
}
else
    header('Location: index.php');


