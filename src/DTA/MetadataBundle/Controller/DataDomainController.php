<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \DTA\MetadataBundle\Model;

/**
 * Route prefix for all action routes, i.e. this page.
 * @Route("/Data")
 */
class DataDomainController extends ORMController {

    /** @inheritdoc */
    public $package = "Data";

    /** @inheritdoc 
     * The menu entries for the different kinds of persons and publications are dynamically added
     * from the database in the __construct() method.
     */
    public $domainMenu = array(
        'work' => array(
            "caption" => "Werke",
            "modelClass" => "Work"),
        'publication' => array(
            "caption" => "Publikationen", 
            "children"=>array(
                array("caption" => "Alle anzeigen", "route" => "home"),
                array("kind" => "divider"),
                // the rest is added in the controller constructor
        )), 
        'person' => array(
            "caption" => "Personen", 
            "children" => array(
                array(
                    "caption" => "Alle anzeigen", 
                    "route" => "home"),
                // the rest is added controller constructor
        )),
        'publishingcompany' => array(
            "caption" => "Verlage", 
            'modelClass' => 'Publishingcompany'),
    );
        
    public function __construct(){
        
        $personRoles = Model\Classification\PersonroleQuery::create()->find();
        foreach($personRoles as $pr ){
            $this->domainMenu['person']['children'][] = array("caption" => $pr->getName(), "route" => "home");
        }

        // contains the php model class names for each publication type
        // see dta_data_schema for explanations of the single types.
        $publicationTypes = array(
            "PublicationM","PublicationDm","PublicationMm","PublicationDs",
            "PublicationMs","PublicationJa","PublicationMms","PublicationJ");
        
        foreach( $publicationTypes as $pt ){
            $this->domainMenu['publication']['children'][] = array("caption" => "", "modelClass" => $pt);
        }
        
    }
    
    /**
     * @Route("/", name="dataDomain")
     */
    public function indexAction() {
        return $this->renderWithDomainData('DTAMetadataBundle:DataDomain:index.html.twig', array(
//            "person" => "array_shift()" //$persont->getRelations() //count($p->getPersonalnames()->getArrayCopy())//[0]->__toString(),
            // get_declared_classes()
        ));
    }

    /**
     * TODO: finalization. remove test data generator.
     * @Route("/generateTestData", name="generateTestData")
     */
//    public function generateTestDataAction() {
//        
//        // name fragment types
//        
//        $vorname = new Model\Data\Namefragmenttype();
//        $vorname->setName("Vorname");
//        $vorname->save();
//
//        $nachname = new Model\Data\Namefragmenttype();
//        $nachname->setName("Nachname");
//        $nachname->save();
//        
//        $adelstitel = new Model\Data\Namefragmenttype();
//        $adelstitel->setName("Adelstitel");
//        $adelstitel->save();
//            
//        $generation = new Model\Data\Namefragmenttype();
//        $generation->setName("Generation");
//        $generation->save();
//        
//        $pseudonym = new Model\Data\Namefragmenttype();
//        $pseudonym->setName("Pseudonym");
//        $pseudonym->save();
//
//        // person types
//        
//        $authorRole = new Model\Classification\Personrole();
//        $authorRole->setName("Autor");
//        $authorRole->setApplicableToWork(true);
//        $authorRole->save();
//        
//        // users
//        $usernames = array("Frank", "Susanne", "Matthias", "Christian", "Carl", "Alexander");
//        
//        foreach ($usernames as $username) {
//            $user = new Model\DtaUser();
//            $user->setUsername($username);
//            $user->save();  
//            $user->setPassword("\$dta010"); 
//            $user->save();
//        }
//        
//        // title types (main-, sub- and short title)
//        
//        $haupttitel = new Model\Titlefragmenttype();
//        $haupttitel->setName("Haupttitel");
//        $haupttitel->save();
//        $untertitel = new Model\Titlefragmenttype();
//        $untertitel->setName("Untertitel");
//        $untertitel->save();
//        $kurztitel = new Model\Titlefragmenttype();
//        $kurztitel->setName("Kurztitel");
//        $kurztitel->save();
//        
//        // person types: work and publication
//        
//        $author = new Model\Personrole();
//        $author->setName('Autor');
//        $author->setApplicableToWork(true);
//        $author->save();
//        
//        $publisher = new Model\Personrole();
//        $publisher->setName('Verleger');
//        $author->setApplicableToPublication(true);
//        $publisher->save();
//        
//        $translator = new Model\Personrole();
//        $translator->setName("Übersetzer");
//        $author->setApplicableToPublication(true);
//        $translator->save();
//        
//        $printer = new Model\Personrole();
//        $printer->setName('Drucker');
//        $author->setApplicableToPublication(true);
//        $printer->save();
//
//        // Workflow, example task types
//        
//        $s1 = new Model\Tasktype();
//        $s1->setName('Aufgabentypen');
//        $s1->makeRoot(); // make this node the root of the tree
//        $s1->save();
//
//        $groupA = new Model\Tasktype();
//        $groupA->setName('Gruppe A: Double Keying');
//        $groupA->insertAsFirstChildOf($s1); // insert the node in the tree
//        $groupA->save();
//        $s3 = new Model\Tasktype();
//        $s3->setName('Textbeschaffung');
//        $s3->insertAsFirstChildOf($groupA); // insert the node in the tree
//        $s3->save();
//        $s4 = new Model\Tasktype();
//        $s4->setName('Vorkorrektur');
//        $s4->insertAsFirstChildOf($s3); // insert the node in the tree
//        $s4->save();
//        $s5 = new Model\Tasktype();
//        $s5->setName('Zoning');
//        $s5->insertAsFirstChildOf($s4); // insert the node in the tree
//        $s5->save();
//
//        $groupB = new Model\Tasktype();
//        $groupB->setName('Gruppe B');
//        $groupB->insertAsNextSiblingOf($groupA);
//        $groupB->save();
//
//        $s7 = new Model\Tasktype();
//        $s7->setName('GrobiZoning');
//        $s7->insertAsFirstChildOf($groupB); // insert the node in the tree
//        $s7->save();
//        
//        return $this->forward("DTAMetadataBundle:Home:index");
//    }

}