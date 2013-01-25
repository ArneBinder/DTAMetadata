<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use \DTA\MetadataBundle\Model;

/**
 * Route prefix for all action routes, i.e. this page.
 * @Route("/daten")
 */
class DataDomainController extends DTABaseController {

    /** @inheritdoc */
    public $domainKey = "DataDomain";

    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Publikationen", "path" => 'home',
            "children" => array(
                array('caption' => "Alle Publikationsarten", 'path' => 'home'),
                array('caption' => "Bücher", 'modelClass' => 'Monograph'),
                array('caption' => "Zeitschriften", 'modelClass' => 'Magazine'),
                array('caption' => "Reihen", 'modelClass' => 'Series'),
                array('caption' => "Essays", 'modelClass' => 'Essay'))),
        array("caption" => "Personen", "path" => 'home',
            "children" => array(
                array('caption' => "Alle Personen", 'path' => 'home'),
                array('caption' => "Autoren", 'modelClass' => 'Author'),
                array('caption' => "Verleger", 'modelClass' => 'Publisher'),
                array('caption' => "Übersetzer", 'modelClass' => 'Translator'),
                array('caption' => "Drucker", 'modelClass' => 'Printer'))),
        array("caption" => "Verlage", 'modelClass' => 'PublishingCompany'),
    );

    /**
     * @Route("/", name="dataDomain")
     */
    public function indexAction() {
        return $this->renderDomainSpecific('DTAMetadataBundle:DataDomain:index.html.twig');
    }

    /**
     * New action, embedded into the data domain page.
     * @param type $className
     * @Route("/neu/{className}", name="DataDomainNewRecord")
     */
    public function newAction($className) {
        $form = $this->genericEditForm($className, 0);
        return $this->renderDomainSpecific('DTAMetadataBundle::autoform.html.twig', array(
                    'className' => $className,
                    'recordId' => 0, // create new record
                    'form' => $form->createView(),
                ));
    }

    /**
     * @Route("/generateTestData", name="generateTestData")
     */
    public function generateTestDataAction() {

        // Testnamensarten
        $vorname = new Model\Namefragmenttype();
        $vorname->setName("Vorname");
        $vorname->save();

        $nachname = new Model\Namefragmenttype();
        $nachname->setName("Nachname");
        $nachname->save();

        // Testnamen
        $user = new Model\User();
        $user->setUsername("Frank");
        $user->save();
        $user = new Model\User();
        $user->setUsername("Susanne");
        $user->save();
        $user = new Model\User();
        $user->setUsername("Matthias");
        $user->save();
        $user = new Model\User();
        $user->setUsername("Christian");
        $user->save();
        $user = new Model\User();
        $user->setUsername("Carl");
        $user->save();
        $user = new Model\User();
        $user->setUsername("Alex");
        $user->save();

        // Titelarten
        $haupttitel = new Model\Titlefragmenttype();
        $haupttitel->setName("Haupttitel");
        $haupttitel->save();
        $untertitel = new Model\Titlefragmenttype();
        $untertitel->setName("Untertitel");
        $untertitel->save();
        $kurztitel = new Model\Titlefragmenttype();
        $kurztitel->setName("Kurztitel");
        $kurztitel->save();

        // Workflow, einige Aufgabenarten
        $s1 = new Model\Tasktype();
        $s1->setName('Aufgabentypen');
        $s1->makeRoot(); // make this node the root of the tree
        $s1->save();

        $groupA = new Model\Tasktype();
        $groupA->setName('Gruppe A: Double Keying');
        $groupA->insertAsFirstChildOf($s1); // insert the node in the tree
        $groupA->save();
        $s3 = new Model\Tasktype();
        $s3->setName('Textbeschaffung');
        $s3->insertAsFirstChildOf($groupA); // insert the node in the tree
        $s3->save();
        $s4 = new Model\Tasktype();
        $s4->setName('Vorkorrektur');
        $s4->insertAsFirstChildOf($s3); // insert the node in the tree
        $s4->save();
        $s5 = new Model\Tasktype();
        $s5->setName('Zoning');
        $s5->insertAsFirstChildOf($s4); // insert the node in the tree
        $s5->save();

        $groupB = new Model\Tasktype();
        $groupB->setName('Gruppe B');
        $groupB->insertAsNextSiblingOf($groupA);
        $groupB->save();

        $s7 = new Model\Tasktype();
        $s7->setName('GrobiZoning');
        $s7->insertAsFirstChildOf($groupB); // insert the node in the tree
        $s7->save();

        // Test Task not adapted to the new structure?
        //        $t = new Task();
//        $vk = \DTA\MetadataBundle\Model\Workflow\TasktypeQuery::create()->filterByName("Vorkorrektur")->findOne();
//        $t->setTasktype($vk);
//        $t->setStart("22.12.2012");
//        $t->setEnd("24.12.2012");
//        $carl = \DTA\MetadataBundle\Model\Workflow\UserQuery::create()->filterByUsername("Carl")->findOne();
//        $t->setResponsibleuserId($carl->getId());
//        $t->save();
//        $rootTask = \DTA\MetadataBundle\Model\TasktypeQuery::create()->findRoot();
    }

}