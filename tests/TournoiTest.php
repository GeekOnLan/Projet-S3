<?php
require_once('includes/myPDOTest.inc.php');

/**
 * Created by PhpStorm.
 * User: Cry
 * Date: 08/12/2015
 * Time: 14:42
 */
class TournoiTest extends PHPUnit_Framework_TestCase
{

    public function testCreateFromIdTrue($id){
        $tournoi = Tournoi::createFromId(0);// Cree dans test precedant
        $this->assertNotNull($tournoi);
    }


    public function testCreateFromIdFalse($id){
        $tournoi = Tournoi::createFromId(1);//Pas Cree dans test precedant
        $this->assertNull($tournoi);
    }

    public function testDelete(){
        $tournoi = Tournoi::createFromId(0);// Cree dans test precedant
        $tournoi->delete();
        $tournoi = Tournoi::createFromId(0);// Supprimé
        $this->assertNotNull($tournoi);
    }

}
