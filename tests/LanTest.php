<?php
require_once('includes/myPDOTest.inc.php');

/**
 * Created by PhpStorm.
 * User: Cry
 * Date: 08/12/2015
 * Time: 14:41
 */
class LanTest extends PHPUnit_Framework_TestCase
{

    public  function testCreateFromIdFalse(){
       $lan = Lan::createFromId(1);
       $this->assertNull($lan);
    }

    public  function testCreateFromIdTrue(){
        Member::createMember("test","test@test.fr",hash(SHA256,"test"),"test","test",12-12-1996);
        $membre = Member::createFromAuth( hash(SHA1,(hash(SHA1,"test").hash(SHA256,"test"))));
        $membre->addLan("testLan","23-12-2015","14 rue du test","Reims"); // LAn d'id 0

        $lan = Lan::createFromId(0);
        $this->assertNotNull($lan);
    }
    public function testUpdate(){
        $lan = Lan::createFromId(0); // LA LAN 0 EST LA LAN CREE DANS Le test precedant
        $lan->update("nouveauTest",'','','','');
        $this->assertEquals("nouveauTest",$lan->getLanName());
    }

    public function delete(){
        $lan = Lan::createFromId(0); // LA LAN 0 EST LA LAN CREE DANS Le test precedant
        $lan->delete();
        $lan = Lan::createFromId(0); // La lan a ete delete don $lan vaut null
        $this->assertNull($lan);
    }



    //LAn dans - d'un mois
    public  function testTetLanFrom(){
        $membre = Member::createFromAuth( hash(SHA1,(hash(SHA1,"test").hash(SHA256,"test"))));
        $membre->addLan("testLan","23-12-2015","14 rue du test","Reims");
        $membre->addLan("testLan2","23-12-2015","14 rue du test","Reims");
        $membre->addLan("testLan3","23-12-2015","14 rue du test","Reims");
        $membre->addLan("testLan4","23-12-2020","14 rue du test","Reims");

        $this->assertEquals(sizeof(Lan::getLanFrom()),3);
    }

    public function testAddTournoi(){
        $lan = Lan::createFromId(0); // LA LAN 0 EST LA LAN CREE DANS Le test precedant
        $lan->addTournoi("theGameofTest","leGrandTournoi",0,10,5);
        $this->assertEquals(0,$lan->getTournoi());
    }

}
