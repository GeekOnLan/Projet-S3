<?php

require_once('includes/myPDOTest.inc.php');

/**
 * Created by PhpStorm.
 * User: Cry
 * Date: 08/12/2015
 * Time: 14:42
 */
class MemberTest extends PHPUnit_Framework_TestCase
{
    public function testCreateMember(){
        $pass =hash(SHA256,"test");
        Member::createMember("test","test@test.fr",$pass,"test","test",12-12-1996);

        $pdo = MyPDO::GetInstance();
        $requete = $pdo->prepare(<<<SQL
			SELECT *
			FROM Membre
			WHERE pseudo = test;
SQL
        );

        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Member);
        $member = $requete->fetch();

        $this->assertNotNull($member);

    }

    public function testCreatefromAuthSuccess(){

        $pass = "test";
        $login = "test";


        $cryptpass = hash(SHA256,$pass);
        $crypt = hash(SHA1,(hash(SHA1,$login).$cryptpass));

        $membre=null;
        try{
            $membre = Member::createFromAuth($crypt);
        }
        catch(Exception $e){}

        $this->assertNotNull($membre);

    }

    public function testCreatefromAuthFail(){
        $crypt ="gdfgfdgf";
        $membre=null;

        try{
            $membre=createFromAuth($crypt);
        }
        catch(Exception $e){
            $membre="erreur";
        }
        $this->assertEquals($membre,"erreur");
    }

    public function testSaveIntoSession(){
        $membre = Member::createFromAuth( hash(SHA1,(hash(SHA1,"test").hash(SHA256,"test"))));
        $membre->saveIntoSession();
        $this->assertEquals($membre,$_SESSION['Member']);
    }

    public function testIsConnectedTrue(){
        $membre = Member::createFromAuth( hash(SHA1,(hash(SHA1,"test").hash(SHA256,"test"))));
        $membre->saveIntoSession();
        $this->assertTrue($membre->isConnected());

    }

    public function testIsConnectedFalse(){
        $membre = Member::createFromAuth( hash(SHA1,(hash(SHA1,"test").hash(SHA256,"test"))));
        $this->assertFalse($membre->isConnected());
    }

    public function testDisconnect(){
        $membre = Member::createFromAuth( hash(SHA1,(hash(SHA1,"test").hash(SHA256,"test"))));
        $membre->saveIntoSession();
        $membre->disconnect();
        $this->assertTrue($membre->isConnected());
    }

    public function testGetInstance(){
        $membre = Member::createFromAuth( hash(SHA1,(hash(SHA1,"test").hash(SHA256,"test"))));
        $membre->saveIntoSession();
        $this->assertEquals($membre,Member::getInstance());
    }

    public function testGetLan(){
        //Code simple
    }
    public function testAddLan(){
        $membre = Member::createFromAuth( hash(SHA1,(hash(SHA1,"test").hash(SHA256,"test"))));
        $membre->addLan("testLan","23-12-2015","14 rue du test","Reims");
        $this->assertEquals(sizeof($membre->getLAN()),1);
    }

    public function testDeleteAccount(){
        $membre = Member::createFromAuth( hash(SHA1,(hash(SHA1,"test").hash(SHA256,"test"))));
        $membre->testDeleteAccount();
        $membre = Member::createFromAuth( hash(SHA1,(hash(SHA1,"test").hash(SHA256,"test"))));
        $this->assertNull($membre);
    }


}
