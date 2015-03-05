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
    
    
    private $listViewWithOptionsFor;
    
    /**
     * Dynamically generate the domain menu.
     */
    public function __construct(){
        
        // use the listViewWithOptions.html.twig template to display publication entities (specialized types and base type)
        $this->listViewWithOptionsFor = Model\Data\PublicationPeer::getValueSet(Model\Data\PublicationPeer::TYPE);
        $this->listViewWithOptionsFor[] = 'Publication';
        $this->listViewWithOptionsFor[] = 'Person';
        
        // retrieve different publication types
        $this->domainMenu['publicationTypes'] = Model\Data\PublicationPeer::getValueSet(Model\Data\PublicationPeer::TYPE);
        
        // retrieve different person roles (Autor, Verleger, Drucker, etc.)        
        $this->domainMenu['personRoles'] = Model\Master\PersonPublicationPeer::getValueSet(Model\Master\PersonPublicationPeer::ROLE);

    }
    
    public function indexAction() {
        
        //return $this->renderWithDomainData('DTAMetadataBundle:Package_Data:index.html.twig', array());
        return $this->redirect($this->generateUrl('Data_genericViewAll', array('className' => 'Publication')));
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
                    $this->generateUrl('Data_genericViewAll',array('package'=>'Data', 'className'=>$publicationType))
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

            $newEntityLink = null;
            if($className==='Person'){
                $newEntityLink = $this->generateUrl("Data_genericCreateOrEdit", array('className'=>$className));
            }else{
                // assume entity is a kind of publication
                $newEntityLink = $this->generateUrl("Data_newPublication", array('publicationType'=>$className!=='Publication'?$className:'Book'));
            }
            $orderableTargets = array_map(function($i){return ++$i;},$modelClass::getRowViewOrderColumnIndices());
            $this->get('logger')->critical("orderableTargets: ".implode(', ',$orderableTargets));
            return $this->renderWithDomainData("DTAMetadataBundle::listViewWithOptions.html.twig", array(
                'className' => $className,
                'columns' => $modelClass::getTableViewColumnNames(),
                'data' => array(),
                'updatedObjectId' => $updatedObjectId,
                'optionsLinkTemplate' => $this->generateUrl("controls", array('className' => $className, 'id'=>'__id__')),
                'newEntityLink' => $newEntityLink,
                'enableSearch' => method_exists(new $classNames["query"], 'sqlFilter'),
                'orderableTargets' => implode(', ',$orderableTargets)
            ));
        }
        
    }

    /** @inheritdoc
     * This provides an additional ID column for list view with options to be able to request a control panel for a clicked publication.
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param String $package
     * @param String $className
     * @param bool $addIdColumn
     */
    public function genericDataTablesDataSourceAction(Request $request, $package, $className, $addIdColumn = false){
        
        if(FALSE === array_search($className, $this->listViewWithOptionsFor)){
            return parent::genericDataTablesDataSourceAction($request, $package, $className);
        }

        // specialized logic for datatables displaying publications (added an id column)
        return parent::genericDataTablesDataSourceAction($request, $package, $className, true);
    }

    protected function getEditLinkForEntity($entity, $caption){
        $specialisation = "";
        if(method_exists($entity,'getSpecialization')){
            $editLink = $this->getEditLinkForObject($entity->getSpecialization());
            $classNameParts = explode('\\', get_class($entity));
            if(array_pop($classNameParts)==="Publication"){
                $specialisation = " <i>(".$this->get('translator')->trans($entity->getSpecializationClassName()).")</i>";
            }

        }else{
            $editLink =  $this->getEditLinkForObject($entity);
        }
        // $deleteLink = $this->generateUrl($package . '_deleteRecord', array(
//                        'package'=>$package, 'className'=>$className, 'recordId'=>$entity->getId()
//                    ));
        return "<a href='$editLink'><span class='glyphicon glyphicon-edit'></span></a>"
//                            ."<a href='$deleteLink'><span class='glyphicon glyphicon-trash'></span></a> "
        ." $caption$specialisation";

    }
    
    /**
     * Returns the content that is dynamically loaded when clicking on a publication in the list generated by the listViewWithOptions.html.twig template.
     * @param type $id The publication to retrieve data for
     * @return Response The html content to display in the controls panel
     */
    public function controlsAction($className, $id) {
        $classNames = $this->relatedClassNames('Data', $className);
        $queryClass = new $classNames["query"];
        $entity = $queryClass::create()->findOneById($id);

        if($className==='Person'){
            if( is_null($entity)){
                $this->get('logger')->critical("Can not retrieve Person with ID $id for display of person controls in ".__FILE__." controlsAction.");
            }
            $relatedPublications = array();
            $relatedPersonPublications = Model\Master\PersonPublicationQuery::create()->findByPersonId($entity->getId());
            foreach($relatedPersonPublications as $relatedPersonPublication){
                $relatedPublications[$relatedPersonPublication->getRole()][] = Model\Data\PublicationQuery::create()->findOneById($relatedPersonPublication->getPublicationId());
            }
            //$this->get('logger')->critical(print_r($relatedPublications, true));
            return $this->render('DTAMetadataBundle:Package_Data:personControls.html.twig', array(
                'person' => $entity,
                'relatedPublications' => $relatedPublications
            ));
        }

        // is the current entity a publication?
        $publication = null;
        if($className==='Publication'){
            $publication = $entity;
        }elseif(method_exists($entity, "getPublication")){
            $publication = $entity->getPublication();
        }

        //$publication = Model\Data\PublicationQuery::create()->findOneById($id);
        if( is_null($publication)){
            $this->get('logger')->critical("Can not retrieve publication with ID $id for display of publication controls in ".__FILE__." controlsAction.");
        }
        return $this->render('DTAMetadataBundle:Package_Data:publicationControls.html.twig', array(
            'publication' => $publication,
        ));
        
    }

    /**
     * Lists persons and person/publication relations with a specific role.
     * @param type $personRoleId
     */
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
        uasort($rows, $compareRow);
        
        return $this->renderWithDomainData('DTAMetadataBundle::listView.html.twig', array(
            'rows' => $rows,
            'columns' => $columns,
            'accessors' => $accessors,
            'title' => $personRoleId
        ));
    }
    
}