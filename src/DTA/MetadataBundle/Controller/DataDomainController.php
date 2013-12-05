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
                array("caption" => "Alle anzeigen", "modelClass"=> 'Publication'),
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
    
    public function viewPersonsByRoleAction($personRoleId) {
        
        $role = Model\Classification\PersonroleQuery::create()
                ->findOneById($personRoleId);
        
        $rows = array();//new \ArrayObject();
        
        $personPublications = Model\Master\PersonPublicationQuery::create()
                ->filterByPersonroleId($personRoleId)
                ->leftJoinWith('Person')
                ->leftJoinWith('Person.Personalname')
                ->leftJoinWith('Personalname.Namefragment')
                ->leftJoinWith('Publication')
                ->leftJoinWith('Publication.Title')
                ->leftJoinWith('Title.Titlefragment')
                ->find();
        foreach ($personPublications as $pp) {

            // the concrete publication object (PublicationM, PublicationJ, etc.) for which the publication provides the basic data fields
            $wrapper = $pp->getPublication();//->getWrapperPublication();
//            $wrapperClassName = $pp->getPublication()->getWrapperPublicationClass();
            // generate a link to the publication 
            $linkTarget = $this->generateUrl("Data_genericCreateOrEdit", array(
                    'className'=> 'Publication', //$wrapperClassName,
                    'recordId'=>$wrapper->getId()
                )
            );

            $linkTo = function($href,$title){return '<a href="'.$href.'">'.$title.'</a>';};
            $rows[] = array(
                'repName' => $pp->getPerson()->getRepresentativePersonalname()->__toString(),
                'context' => $linkTo($linkTarget, $pp->getPublication()->getTitle())
            );
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