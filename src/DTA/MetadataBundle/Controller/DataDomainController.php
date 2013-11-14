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
                ->joinWith('Personalname')
                ->joinWith('Personalname.Namefragment')
                ->joinWith('Namefragment.Namefragmenttype')
                ->orderBy('Namefragmenttype.id', \Criteria::DESC)   // to sort by last name
                ->orderBy('Namefragment.name')
                ->find();
        
//        $sortByPersonalname = function($p1, $p2){ return strcmp($p1->getRepresentativePersonalname()->__toString(), $p2->getRepresentativePersonalname()->__toString());};
//        $records = array();
//        foreach($persons as $person){
//            $records[] = $person;
//        }
//        extremely slow
//        @uasort($records, $sortByPersonalname);
        return $this->renderWithDomainData('DTAMetadataBundle:ORM:genericViewAll.html.twig', array(
                'className' => 'Person',
                'columns' => Model\Data\Person::getTableViewColumnNames(),
                'data' => $persons,
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
    
}