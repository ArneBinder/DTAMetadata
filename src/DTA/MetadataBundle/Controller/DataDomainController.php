<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use \DTA\MetadataBundle\Model;

class DataDomainController extends ORMController {

    /** @inheritdoc */
    public $package = "Data";

    /** @inheritdoc 
     * The menu entries for the different kinds of persons and publications are dynamically added
     * from the database in the __construct() method.
     */
    public $domainMenu = array(
//        'work' => array(
//            "caption" => "Werke",
//            "modelClass" => "Work"),
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
                    "modelClass" => "Person"),
                // the rest is added controller constructor
        )),
        'publishingcompany' => array(
            "caption" => "Verlage", 
            'modelClass' => 'Publishingcompany'),
        array("caption" => "Schriftarten", "modelClass" => 'Font'),
        array("caption" => "Sprachen", "modelClass" => 'Language'),
    );
    
    /**
     * Dynamically generate the domain menu.
     */
    public function __construct(){
        
        // retrieve different person roles (Autor, Verleger, Drucker, etc.)        
        $personRoles = Model\Classification\PersonroleQuery::create()->find();
        foreach($personRoles as $pr ){
            $this->domainMenu['person']['children'][] = array(
                "caption" => $pr->getName(), 
                "route" => "viewPersonsByRole", 
                "parameters" => array('personRoleId'=>$pr->getId())
            );
        }

        // render different publication types
        // see dta_data_schema for explanations on the single types.
        $publicationTypes = array(
            "PublicationM","PublicationDm","PublicationMm","PublicationDs",
            "PublicationMs","PublicationJa","PublicationMms","PublicationJ");
        foreach( $publicationTypes as $pt ){
            $this->domainMenu['publication']['children'][] = array("caption" => "", "modelClass" => $pt);
        }
        
    }
    
    public function indexAction() {
        return $this->renderWithDomainData('DTAMetadataBundle:Package_Data:index.html.twig', array(
//            "person" => "array_shift()" //$persont->getRelations() //count($p->getPersonalnames()->getArrayCopy())//[0]->__toString(),
            // get_declared_classes()
        ));
    }

//    public function lexicographicalComparison($s1, $s2){
//        for($i = 0; $i < max(array(count($s1),count($s2))); $i++){
//            if(ord($s1[$i]) > ord($s2[$i]))
//                return 1;
//            elseif(ord($s1[$i]) < ord($s2[$i]))
//                return -1;
//        }
//        return 0;
//    }
    
    public function viewPersonsAction() {
        $persons = Model\Data\PersonQuery::create()
                ->find();
        $sortByPersonalname = function($p1, $p2){ return strcmp($p1->getRepresentativePersonalname()->__toString(), $p2->getRepresentativePersonalname()->__toString());};
        
        $records = array();
        foreach($persons as $person){
            $records[] = $person;
        }
        @uasort($records, $sortByPersonalname);
        return $this->renderWithDomainData('DTAMetadataBundle:ORM:genericViewAll.html.twig', array(
                'className' => 'Person',
                'columns' => Model\Data\Person::getTableViewColumnNames(),
                'data' => $records,
            ));
    }

    public function viewPersonsByRoleAction($personRoleId) {
        
        $role = Model\Classification\PersonroleQuery::create()
                ->findOneById($personRoleId);
        
        $rows = array();//new \ArrayObject();
        
        if( $role->getApplicableToPublication() ) {
            $personPublications = Model\Master\PersonPublicationQuery::create()
                    ->filterByPersonroleId($personRoleId)
                    ->find();
            foreach ($personPublications as $pp) {
                
                // the concrete publication object (PublicationM, PublicationJ, etc.) for which the publication provides the basic data fields
                $wrapper = $pp->getPublication()->getWrapperPublication();
                $wrapperClassName = $pp->getPublication()->getWrapperPublicationClass();
                // generate a link to the publication 
                $linkTarget = $this->generateUrl("Data_genericCreateOrEdit", array(
                        'className'=> $wrapperClassName,
                        'recordId'=>$wrapper->getId()
                    )
                );
                
                $linkTo = function($href,$title){return '<a href="'.$href.'">'.$title.'</a>';};
                $rows[] = array(
                    'repName' => $pp->getPerson()->getRepresentativePersonalname()->__toString(),
                    'context' => $linkTo($linkTarget, $pp->getPublication()->getWork()->getTitle())
                );
            }
        }
        
        if( $role->getApplicableToWork() ) {
            $personWorks = Model\Master\PersonWorkQuery::create()
                    ->filterByPersonroleId($personRoleId)
                    ->find();
            foreach ($personWorks as $pp) {
                $rows[] = array(
                    'repName' => $pp->getPerson()->getRepresentativePersonalname()->__toString(),
                    'context' => $pp->getWork()->getTitle()
                );
            }
        }
        
        $columns = array('Name', 'Publikation/Werk');
        $accessors = array('repName', 'context');
        $compareRow = function($a, $b){ return strcmp($a['repName'],$b['repName']); };
        uasort($rows, $compareRow);
        return $this->renderWithDomainData('DTAMetadataBundle::listView.html.twig', array(
            'rows' => $rows,
            'columns' => $columns,
            'accessors' => $accessors,
            'title' => $role->getName()
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