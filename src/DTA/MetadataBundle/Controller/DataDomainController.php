<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use \DTA\MetadataBundle\Model;

class DataDomainController extends ORMController {

    /** @inheritdoc */
    public $package = "Data";

    /** @inheritdoc 
     * The menu entries for the different kinds of persons and publications are dynamically added
     * from the database in the __construct() method.
     */
    public $domainMenu = array(
        'specializedTemplate' => 'DTAMetadataBundle:Package_Data:domainMenu.html.twig',
        'publicationTypes' => 'added in constructor',
        'personRoles' => 'added in constructor',
    );
    
    private $listViewWithOptionsFor = array("Publication", "Book", "Volume", "MultiVolume", "Chapter", "Journal", "Article", "Series");
    
    /**
     * Dynamically generate the domain menu.
     */
    public function __construct(){
        
        // retrieve different publication types
        $this->domainMenu['publicationTypes'] = array('Book', 'Volume', 'MultiVolume', 'Chapter', 'Journal', 'Article', 'Series');
        
        // retrieve different person roles (Autor, Verleger, Drucker, etc.)        
        $this->domainMenu['personRoles'] = Model\Master\PersonPublicationPeer::getValueSet(Model\Master\PersonPublicationPeer::ROLE);

    }
    
    public function indexAction() {
        
        return $this->renderWithDomainData('DTAMetadataBundle:Package_Data:index.html.twig', array(
        ));
    }
    /**
     * Handles creation of publications (since for some publication types specialized classes exist, a bit of extra logic is required.)
     * @param Request $request the request wrapper
     * @param string $publicationType The publication type as enumerated by the {@link Model\Data\PublicationPeer Publication peer} class. 
     */
    public function newPublicationAction(Request $request, $publicationType, $recordId = NULL){
        
        $specializedPublication = Model\Data\Publication::create($publicationType);
        $result = parent::genericCreateOrEdit($request, $specializedPublication);
        
        switch( $result['transaction'] ){
            case "complete":
                $this->addSuccessFlash("Änderungen vorgenommen.");
                return $this->redirect(
                    $this->generateUrl('Data_viewPublicationsByType',array('publicationType'=>$publicationType))
                );
            case "edit":
            case "create":
                return $this->renderWithDomainData("DTAMetadataBundle:ORM:createOrEdit.html.twig", array(
                    'form' => $result['form']->createView(),
                    'transaction' => $result['transaction'],    // whether the form is for edit or create
                    'className' => $specializedPublication->getPublication()->getSpecializationClassName(),          
                    'entityName' => $publicationType,   // this will be displayed in the headline (i.e. book is just a publication with type=book but it should read "create new book" and not "create new publication"
                    'recordId' => $specializedPublication->getId(),
                    'sendDataTo' => $this->generateUrl('Data_newPublication', array('publicationType'=>$publicationType)),
                ));
        }
        
    }
    
    public function genericViewAllAction($package, $className, $updatedObjectId = 0){
        
        if(FALSE === array_search($className, $this->listViewWithOptionsFor)){
            return parent::genericViewAllAction($package, $className);
        } else {
            $classNames = $this->relatedClassNames($package, $className);

            // for retrieving the column names
            $modelClass = new $classNames["model"];

            return $this->renderWithDomainData("DTAMetadataBundle::listViewWithOptions.html.twig", array(
                'className' => $className,
                'columns' => $modelClass::getTableViewColumnNames(),
                'data' => array(),
                'updatedObjectId' => $updatedObjectId,
                'optionsLinkTemplate' => $this->generateUrl("publicationControls", array('publicationId'=>'__id__')),
            ));
        }
        
    }

    /** @inheritdoc
     * This provides an additional ID column for list view with options to be able to request a control panel for a clicked publication.
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $package
     * @param type $className
     */
    public function genericDataTablesDataSourceAction(Request $request, $package, $className){
        
        if(FALSE === array_search($className, $this->listViewWithOptionsFor)){
            return parent::genericDataTablesDataSourceAction($request, $package, $className);
        }
    
        // ------------------------------------------------------------------------
        // specialized logic for datatables displaying publications (added an id column) 
        // ------------------------------------------------------------------------
        
        $classNames = $this->relatedClassNames($package, $className);
        $modelClass = new $classNames["model"];

        $columns = $modelClass::getTableViewColumnNames();
        $query = $modelClass::getRowViewQueryObject();

        $totalRecords = $query->count();

        // Output
        $response = array(
            "sEcho" => intval($request->get('sEcho')),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $query->count(),
            "aaData" => array()
        );

        $entities = $this->findPaginatedSortedFiltered($request, $package, $className);

        foreach($entities as $entity) {
            $row = array($entity->getId());
            for ($i = 0; $i < count($columns); $i++) {
                $column = $columns[$i];
                $attribute = $entity->getAttributeByTableViewColumName($column);
                if(is_object($attribute)){
                    $value = $attribute->__toString();
                } else {
                    $value = $attribute;
                }
                // add an edit button to the efirst column entry
                if($i === 0){
                    $editLink = $this->generateUrl($package . '_genericCreateOrEdit', array(
                        'package'=>$package, 
                        'className'=>$className, 
                        'recordId'=>$entity->getId()
                    ));
                    $row[] = "<a href='$editLink'><span class='glyphicon glyphicon-edit'></span></a> $value";
                } else {
                    $row[] = $value;
                }
            }
//            $row[] = "<a href='#'>click</a>";
            $response['aaData'][] = $row;
        }

        return new Response(json_encode( $response ));

    }
    public function publicationControlsAction($publicationId) {
        
        $publication = Model\Data\Publication::getRowViewQueryObject()->findOneById($publicationId);
        
        return $this->render('DTAMetadataBundle:Package_Data:publicationControls.html.twig', array(
            'publication' => $publication,
        ));
        
    }

    public function viewPersonsByRoleAction($personRoleId) {
        
        $rows = array();//new \ArrayObject();
        $personPublications = Model\Master\PersonPublicationQuery::create()
                ->filterByRole($personRoleId)
                ->usePublicationQuery()
                    // multi-volumes are enough, don't show each volume separately
                    ->filterByType(Model\Data\PublicationPeer::TYPE_VOLUME, \Criteria::NOT_EQUAL)
                ->endUse()
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
            'title' => $personRoleId
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