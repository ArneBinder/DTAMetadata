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
        $publicationTypes = Model\Data\om\BasePublicationPeer::getValueSet(Model\Data\PublicationPeer::TYPE);
        foreach( $publicationTypes as $pt => $type){
            $this->domainMenu['publication']['children'][] = array(
                "caption" => $type, 
                "route" => 'viewPublicationsByType',
                "parameters" => array('publicationType'=>$type),
            );
        }
        
    }
    
    public function indexAction() {
        return $this->renderWithDomainData('DTAMetadataBundle:Package_Data:index.html.twig', array(
//            "person" => "array_shift()" //$persont->getRelations() //count($p->getPersonalnames()->getArrayCopy())//[0]->__toString(),
            // get_declared_classes()
        ));
    }
    
    public function publicationControlsAction($publicationId) {
        
        $publication = Model\Data\Publication::getRowViewQueryObject()->findOneById($publicationId);
        
        return $this->render('DTAMetadataBundle:Package_Data:publicationControls.html.twig', array(
            'publication' => $publication,
        ));
        
    }

    /** Render list of all publications with publication options panel. */
    public function viewPublicationsAction(){
        
        $records = Model\Data\Publication::getRowViewQueryObject()->find();
        
        $columns = Model\Data\Publication::getTableViewColumnNames();
        
        $rows = array();
        foreach ($records as $pub) {

            $linkTarget = $this->generateUrl("Data_genericCreateOrEdit", array(
                    'className'=> 'Publication', 
                    'recordId'=>$pub->getId()
                )
            );

            $linkTo = function($href,$title){return '<a href="'.$href.'">'.$title.'</a>';};
            $row = array();
            foreach ($columns as $col) {
                $row[$col] = $pub->getAttributeByTableViewColumName($col);
            }
            $row['id'] = $pub->getId();
            $rows[] = $row;
        }
        
        return $this->renderWithDomainData("DTAMetadataBundle::listViewWithOptions.html.twig", array(
                    'title' => 'Publikationen',
                    'columns' => Model\Data\Publication::getTableViewColumnNames(),
                    'rows' => $rows,
                    'updatedObjectId' => 0,
                    'accessors' => $columns,
                    'optionsLinkTemplate' => $this->generateUrl("publicationControls", array('publicationId'=>'__id__'))
                ));
        
    }
    
    public function viewPersonsByRoleAction($personRoleId) {
        
        $role = Model\Classification\PersonroleQuery::create()
                ->findOneById($personRoleId);
        
        $rows = array();//new \ArrayObject();
        $personPublications = Model\Master\PersonPublicationQuery::create()
                ->filterByPersonroleId($personRoleId)
                ->usePublicationQuery()
                    ->filterByType(Model\Data\PublicationPeer::TYPE_VOLUME, \Criteria::NOT_EQUAL)->endUse()
                ->leftJoinWith('Person')
                ->leftJoinWith('Person.Personalname')
                ->leftJoinWith('Personalname.Namefragment')
                ->leftJoinWith('Publication')
                ->leftJoinWith('Publication.Title')
                ->leftJoinWith('Title.Titlefragment')
                ->find();
        
        foreach ($personPublications as $pp) {

            // generate a link to the publication 
            $linkTarget = $this->generateUrl("Data_genericCreateOrEdit", array(
                    'className'=> 'Publication', //$wrapperClassName,
                    'recordId'=>$pp->getPublication()->getId()
                )
            );

            $linkTo = function($href,$title){return '<a href="'.$href.'">'.$title.'</a>';};
            $rows[] = array(
                'repName' => $pp->getPerson()->getRepresentativePersonalname()->__toString(),
                'context' => $linkTo($linkTarget, $pp->getPublication()->getTitle()),
                'publicationType' => $pp->getPublication()->getType(),
            );
        }
        
        $columns = array('Name', 'Publikation','Publikationstyp');
        $accessors = array('repName', 'context', 'publicationType');
        $compareRow = function($a, $b){ return strcmp($a['repName'],$b['repName']); };
        // doesn't noticeably impact performance, but is maybe unnecessary if the javascript sorts the table anyways
        uasort($rows, $compareRow);
        return $this->renderWithDomainData('DTAMetadataBundle::listView.html.twig', array(
            'rows' => $rows,
            'columns' => $columns,
            'accessors' => $accessors,
            'title' => $role->getName()
        ));
    }

    public function viewPublicationsByTypeAction($publicationType) {
        
        $rows = array();
        
        $publications = 
                Model\Data\Publication::getRowViewQueryObject()
                ->filterByType($publicationType)
                ->find();
        
        foreach ($publications as $pub) {

            $linkTarget = $this->generateUrl("Data_genericCreateOrEdit", array(
                    'className'=> 'Publication', 
                    'recordId'=>$pub->getId()
                )
            );

            $linkTo = function($href,$title){return '<a href="'.$href.'">'.$title.'</a>';};
            $rows[] = array(
                'context' => $linkTo($linkTarget, $pub->getTitleString()),
                'repName' => $pub->getFirstAuthor(),
                'id'      => $pub->getId()
            );
        }
        
        $columns = array('Titel', 'Erster Autor');
        $accessors = array('context', 'repName');
//        $compareRow = function($a, $b){ return strcmp($a['context'],$b['context']); };
//        uasort($rows, $compareRow);
        return $this->renderWithDomainData('DTAMetadataBundle::listViewWithOptions.html.twig', array(
            'rows' => $rows,
            'columns' => $columns,
            'accessors' => $accessors,
            'title' => $publicationType,
            'optionsLinkTemplate' => $this->generateUrl("publicationControls", array('publicationId'=>'__id__')),
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
    
}