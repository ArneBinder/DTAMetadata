<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DTA\MetadataBundle\Model;
use DTA\MetadataBundle\Form;

class HomeController extends DTABaseController {

    /** @inheritdoc */
    public $domainKey = "Home";

    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Zuletzt Angelegt", "path" => 'home'),
        array("caption" => "Zuletzt Bearbeitet", "path" => 'home'),
        array("caption" => "Zuletzt Angesehen", "path" => 'home'),
    );

    /**
     * @return void
     * @Route("/home", name="home")
     */
    public function indexAction(Request $request) {

//        $peerClassName = "DTA\\MetadataBundle\\Model\\Workflow\\" . $className . "Peer";//Workflow\Task::PEER;
//        $peer = new $peerClassName;
        $d = new Model\Datespecification();
//        $d->setYear(2012);
//        $d->setYearIsReconstructed(true);

        $form = $this->createFormBuilder($d)
                ->add("year", "number")
                ->getForm();

        if ($request->isMethod("POST")) {
            $form->bind($request);
            if ($form->isValid()) {
                
            }
        }

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
        return $this->renderWithDomainMenu('DTAMetadataBundle:Home:base.html.twig', array(
                    'form' => $form->createView(),
                    'persistedObject' => $d, //$peer->getTableMap()->getDatabaseMap(),//getFieldNames(),
                    'lastBooksCreatedByUser' => $lastBooksCreatedByUser,
                    'lastBooksViewedByUser' => $lastBooksViewedByUser,
                ));
    }

    /**
     * @param type $className
     * @Route("/newTitle", name="newTitle")
     */
    public function newTitleAction(Request $request) {

//        var_dump($className);
        $objClassName = "DTA\\MetadataBundle\\Model\\Title";
        $objFormClassName = "DTA\\MetadataBundle\\Form\\Type\\TitleType";
        $obj = new $objClassName;

        $form = $this->createForm(new $objFormClassName, $obj);

        if ($request->isMethod("POST")) {
            $form->bind($request);
            if ($form->isValid())
                $obj->save();
        }

        return $this->renderWithDomainMenu('DTAMetadataBundle:Model:title.html.twig', array(
                    'persistedObject' => 'persisted.',
                    'form' => $form->createView(),
                ));
    }
    
    /**
     * @param type $className
     * @Route("/home/genericNew/{className}", name="genericNew")
     */
    public function genericNewAction(Request $request, $className) {

//        var_dump($className);
        $objClassName = "DTA\\MetadataBundle\\Model\\" . $className;
        $objFormTypeClassName = "DTA\\MetadataBundle\\Form\\Type\\" . $className . "Type";
        $obj = new $objClassName;
        
//        $titlefragment = new Model\Titlefragment();
//        $titlefragment->setName("Fragmente einer Sprache der Liebe");
//        $titlefragment->setTitlefragmenttypeId(1);
//        $titlefragment->setRank(1);
//        $obj->addTitlefragment($titlefragment);
//        
//        $titlefragment2 = new Model\Titlefragment();
//        $titlefragment2->setName("Lyrische Exzerpte");
//        $titlefragment2->setTitlefragmenttypeId(2);
//        $titlefragment2->setRank(2);
//        $obj->addTitlefragment($titlefragment2);
//        
//        $obj->save();
//        
//        $titlefragment3 = new Model\Titlefragment();
//        $titlefragment3->setName("Another subtitle");
//        $titlefragment3->setTitlefragmenttypeId(2);
//        $titlefragment3->setRank(3);
//        $obj->addTitlefragment($titlefragment3);
//        $titlefragment = new Model\Titlefragment();
//        $obj->addTitlefragment($titlefragment);

        $form = $this->createForm(new $objFormTypeClassName, $obj);

        $bindTest = -1;

        if ($request->isMethod("POST")) {
            $form->bind($request);
            $bindTest = $form->getData();//$obj->getTitlefragments();
//            $tf = new Model\Titlefragment();
            if ($form->isValid()){
                $obj->save();
            }
                
//            $bindTest = $bindTest[0]->getTitleId();
        }

        // the logical template name DTAMetadataBundle:A:b resolves to src/DTA/MetadataBundle/Resources/views/A/b
        return $this->renderWithDomainMenu('DTAMetadataBundle::autoform.html.twig', array(
                    'className' => $className,
                    'persistedObject' => $bindTest,
                    'form' => $form->createView(),
                ));
    }

    /**
     * @Route("/new/nameFragment", name="gack")
     */
    public function newNameFragmentAction(Request $request) {
        $obj = new Model\Namefragment();
        $form = $this->createForm(new Form\Type\NamefragmentType(), $obj);

        if ($request->isMethod("POST")) {
            $form->bind($request);
            if ($form->isValid()) {
                $obj->save();
            }
        }

        return $this->render('DTAMetadataBundle:Home:index.html.twig', array(
                    'persistedObject' => $obj,
                    'form' => $form->createView(),
                ));
    }

    /**
     * @Route("/new/personalName", name="newPersonalName")
     */
    public function newPersonalNameAction(Request $request) {
        $obj = Model\PersonalnameQuery::create()->findOneById(1);
        $form = $this->createForm(new Form\Type\PersonalnameType(), $obj);
        $result = "GET request";
        if ($this->getRequest()->getMethod() == 'POST') {//$request->isMethod("POST")) {
            $form->bind($request);
            $result = $form->getErrorsAsString();

            if ($form->isValid()) {
                $obj->save();
                $result = "saved.";
            }
        }
        return $this->render('DTAMetadataBundle:Home:index.html.twig', array(
                    'persistedObject' => $result,
                    'form' => $form->createView(),
                ));
    }

    /**
     * @Route("/generate/testData", name="generateTestData")
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
    }

}
