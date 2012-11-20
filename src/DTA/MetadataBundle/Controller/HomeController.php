<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DTA\MetadataBundle\Model\User;
//use DTA\MetadataBundle\Model\Workflow\Task;

class HomeController extends Controller {

    public function indexAction() {

////        $this->testData();
        $t = new \DTA\MetadataBundle\Model\Workflow\Task();
        $vk = \DTA\MetadataBundle\Model\Workflow\TasktypeQuery::create()->filterByName("Vorkorrektur")->findOne();
        $t->setTasktype($vk);
        $t->setStart("22.12.2012");
        $t->setEnd("24.12.2012");
        $carl = \DTA\MetadataBundle\Model\Workflow\UserQuery::create()->filterByUsername("Carl")->findOne();
        $t->setResponsibleuserId($carl->getId());
        $t->save();
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
                    'persistedObject' => $t,
                    'lastBooksCreatedByUser' => $lastBooksCreatedByUser,
                    'lastBooksViewedByUser' => $lastBooksViewedByUser
                ));
    }
    
    
    private function testData() {
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

        $s2 = new TaskType();
        $s2->setName('Gruppe A: Double Keying');
        $s2->insertAsFirstChildOf($s1); // insert the node in the tree
        $s2->save();
        $s3 = new TaskType();
        $s3->setName('Textbeschaffung');
        $s3->insertAsFirstChildOf($s2); // insert the node in the tree
        $s3->save();
        $s4 = new TaskType();
        $s4->setName('Vorkorrektur');
        $s4->insertAsFirstChildOf($s3); // insert the node in the tree
        $s4->save();
        $s5 = new TaskType();
        $s5->setName('Zoning');
        $s5->insertAsFirstChildOf($s4); // insert the node in the tree
        $s5->save();

        $s6 = new TaskType();
        $s6->setName('Gruppe B');
        $s6->insertAsNextSiblingOf($s2);
        $s6->save();

        $s7 = new TaskType();
        $s7->setName('GrobiZoning');
        $s7->insertAsFirstChildOf($s6); // insert the node in the tree
        $s7->save();
        
        
        
    }


}
