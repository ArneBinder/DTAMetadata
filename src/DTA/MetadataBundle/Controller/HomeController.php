<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use DTA\MetadataBundle\Model\Workflow;
use DTA\MetadataBundle\Model\Description;

class HomeController extends Controller {

    public function indexAction($className) {

        $peerClassName = "DTA\\MetadataBundle\\Model\\Workflow\\" . $className . "Peer";//Workflow\Task::PEER;
        $peer = new $peerClassName;
//        $d = new Description\Datespecification();
//        $d->setYear(2012);
//        $d->setYearIsReconstructedFlag(true);
        
////        $this->testData();
//        $t = new Task();
//        $vk = \DTA\MetadataBundle\Model\Workflow\TasktypeQuery::create()->filterByName("Vorkorrektur")->findOne();
//        $t->setTasktype($vk);
//        $t->setStart("22.12.2012");
//        $t->setEnd("24.12.2012");
//        $carl = \DTA\MetadataBundle\Model\Workflow\UserQuery::create()->filterByUsername("Carl")->findOne();
//        $t->setResponsibleuserId($carl->getId());
//        $t->save();
//        $rootTask = \DTA\MetadataBundle\Model\TasktypeQuery::create()->findRoot();

        $lastBooksCreatedByUser = array(
            array("title" => "Aesthetische Feldzüge", "date" => "14.11.2012 / 16:54:54"),
            array("title" => "Die Frau und der Sozialismus", "date" => "11.11.2012 / 16:54:54")
        );
        $lastBooksViewedByUser = array(
            array("title" => "Aesthetische Feldzüge", "date" => "14.11.2012 / 16:54:54"),
            array("title" => "Die Frau und der Sozialismus", "date" => "11.11.2012 / 16:54:54")
        );
        return $this->render('DTAMetadataBundle:Home:index.html.twig', array(
                    'persistedObject' => $peer->getTableMap()->getDatabaseMap(),//getFieldNames(),
                    'lastBooksCreatedByUser' => $lastBooksCreatedByUser,
                    'lastBooksViewedByUser' => $lastBooksViewedByUser
                ));
    }
    
    
    private function testData() {
        
        $vorname = new Description\Namefragmenttype();
        $vorname->setName("Vorname");
        $vorname->save();
        
        $nachname = new Description\Namefragmenttype();
        $nachname->setName("Nachname");
        $nachname->save();
        
        $user = new User();
        $user->setUsername("Frank");
        $user->save();
        $user = new User();
        $user->setUsername("Susanne");
        $user->save();
        $user = new User();
        $user->setUsername("Matthias");
        $user->save();
        $user = new User();
        $user->setUsername("Christian");
        $user->save();
        $user = new User();
        $user->setUsername("Carl");
        $user->save();
        $user = new User();
        $user->setUsername("Alex");
        $user->save();

        $s1 = new TaskType();
        $s1->setName('Aufgabentypen');
        $s1->makeRoot(); // make this node the root of the tree
        $s1->save();

        $groupA = new TaskType();
        $groupA->setName('Gruppe A: Double Keying');
        $groupA->insertAsFirstChildOf($s1); // insert the node in the tree
        $groupA->save();
        $s3 = new TaskType();
        $s3->setName('Textbeschaffung');
        $s3->insertAsFirstChildOf($groupA); // insert the node in the tree
        $s3->save();
        $s4 = new TaskType();
        $s4->setName('Vorkorrektur');
        $s4->insertAsFirstChildOf($s3); // insert the node in the tree
        $s4->save();
        $s5 = new TaskType();
        $s5->setName('Zoning');
        $s5->insertAsFirstChildOf($s4); // insert the node in the tree
        $s5->save();

        $groupB = new TaskType();
        $groupB->setName('Gruppe B');
        $groupB->insertAsNextSiblingOf($groupA);
        $groupB->save();

        $s7 = new TaskType();
        $s7->setName('GrobiZoning');
        $s7->insertAsFirstChildOf($groupB); // insert the node in the tree
        $s7->save();
        
        
        
    }


}
