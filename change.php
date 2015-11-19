<?php
    require_once('includes/autoload.inc.php');
    require_once('includes/connectedMember.inc.php');
    require_once('includes/myPDO.inc.php');
    require_once('includes/utility.inc.php');

/*
 * change le mot de passe du membre
 */
if(verify($_POST,'lastPassHidden') && verify($_POST,'newPassHidden')) {
    $pdo = myPDO::getInstance();
    $stmt = $pdo->prepare(<<<SQL
			SELECT *
			FROM Membre
			WHERE idMembre=:id
			  AND password=:lastPass;
SQL
    );
    $stmt->execute(array("id"=>Member::getInstance()->getId(), "lastPass" => $_POST['lastPassHidden']));
    if($stmt->fetch()!==false) {
        $pdo = myPDO::getInstance();
        $stmt = $pdo->prepare(<<<SQL
			UPDATE Membre
			SET password=:pass
			WHERE idMembre=:id
			  AND password=:lastPass;
SQL
        );
        $stmt->execute(array("id"=>Member::getInstance()->getId(), "lastPass" => $_POST['lastPassHidden'], "pass" => $_POST['newPassHidden']));
        Member::disconnect();
        header('Location: index.php');
    }
    else{
        header('Location: profil.php?message=mot de passe incorect');
    }

}
else
    header('Location: index.php');


