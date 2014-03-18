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
        'specializedTemplate' => 'DTAMetadataBundle:Package_Data:domainMenu.html.twig',
        'publicationTypes' => 'added in constructor',
        'puersonRoles' => 'added in constructor',
    );
    
    /**
     * Dynamically generate the domain menu.
     */
    public function __construct(){
        
        // retrieve different publication types
        $this->domainMenu['publicationTypes'] = Model\Data\om\BasePublicationPeer::getValueSet(Model\Data\PublicationPeer::TYPE);
        
        // retrieve different person roles (Autor, Verleger, Drucker, etc.)        
        $this->domainMenu['personRoles'] = Model\Master\PersonPublicationPeer::getValueSet(Model\Master\PersonPublicationPeer::ROLE);

    }
    
    /**
     * Handles creation of publications (since for some publication types specialized classes exist, a bit of extra logic is required.)
     * @param Request $request the request wrapper
     * @param string $publicationType The publication type as enumerated by the {@link Model\Data\PublicationPeer Publication peer} class. 
     */
    public function newPublicationAction(Request $request, $publicationType){
        
        $basepublication = new Model\Data\Publication();
        $basepublication->setType($publicationType);

        $specializedPublication = NULL;
        
        switch ($publicationType) {
            case Model\Data\PublicationPeer::TYPE_BOOK:
            case Model\Data\PublicationPeer::TYPE_JOURNAL:
                $specializedPublication = $basepublication;
                break;
            case Model\Data\PublicationPeer::TYPE_SERIES:
                $specializedPublication = new Model\Data\Series();
                $specializedPublication->setPublication($basepublication);
                break;
            case Model\Data\PublicationPeer::TYPE_CHAPTER:
                $specializedPublication = new Model\Data\Chapter();
                $specializedPublication->setPublication($basepublication);
                break;
            case Model\Data\PublicationPeer::TYPE_VOLUME:
                $specializedPublication = new Model\Data\Volume();
                $specializedPublication->setPublication($basepublication);
                break;
            case Model\Data\PublicationPeer::TYPE_MULTIVOLUME:
                $specializedPublication = new Model\Data\MultiVolume();
                $specializedPublication->setPublication($basepublication);
                break;
            case Model\Data\PublicationPeer::TYPE_ARTICLE:
                $specializedPublication = new Model\Data\Article();
                $specializedPublication->setPublication($basepublication);
                break;
            default:
                throw new \Exception("Don't know how to create publication type $publicationType.");
                break;
        }
        
        $result = parent::genericCreateOrEdit($request, $specializedPublication);
        
//        $classNameParts = explode('\\',get_class($specializedPublication));
//        $className = array_pop($classNameParts);
        
        switch( $result['transaction'] ){
            case "recordNotFound":
                $this->addErrorFlash("Der gewünschte Datensatz kann nicht bearbeitet werden, weil er nicht gefunden wurde.");
                return $this->redirect(
                    $this->generateUrl('Data_genericViewAll',array('package'=>'Data', 'className'=>$className))
                );
            case "complete":
                $this->addSuccessFlash("Änderungen vorgenommen.");
                return $this->redirect(
                    $this->generateUrl('Data_genericViewAll',array('package'=>'Data', 'className'=>$className))
                );
            case "edit":
            case "create":
                return $this->renderWithDomainData("DTAMetadataBundle:ORM:createOrEdit.html.twig", array(
                    'form' => $result['form']->createView(),
                    'transaction' => $result['transaction'],    // whether the form is for edit or create
                    'className' => $basepublication->getSpecializationClassName(),          
                    'entityName' => $publicationType,   // this will be displayed in the headline (i.e. book is just a publication with type=book but it should read "create new book" and not "create new publication"
                    'recordId' => $specializedPublication->getId(),
//                    'publication' => $result['form']->createView(),
                ));
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

    /** Render list of all publications with publication options panel. (The option panel's why the generic list view isn't used)
     *  the route is /Data/showAll/Publication */
    public function viewPublicationsAction(){
        
        // use optimized query as defined in the data schema (dta_data_schema.xml)
        $records = Model\Data\Publication::getRowViewQueryObject()->find();
        
        $columns = Model\Data\Publication::getTableViewColumnNames();
        
        $rows = array();
        foreach ($records as $pub) {
            $row = array();
            foreach ($columns as $col) {
                $row[$col] = $pub->getAttributeByTableViewColumName($col);
            }
            $row['id'] = $pub->getId();
            
            $editLinkTarget = $this->generateUrl("Data_genericCreateOrEdit", array(
                'className'=>$pub->getSpecializationClassName(), 
                'recordId'=>$pub->getId(),
            ));
            $row['Titel'] = "<a href='$editLinkTarget'>$row[Titel]</a>";
            $rows[] = $row;
        }
        
        return $this->renderWithDomainData("DTAMetadataBundle::listViewWithOptions.html.twig", array(
                    'title' => 'Publikationen',
                    'columns' => $columns,
                    'rows' => $rows,
                    'updatedObjectId' => 0,
                    'accessors' => $columns,
                    'optionsLinkTemplate' => $this->generateUrl("publicationControls", array('publicationId'=>'__id__'))
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

    public function viewPublicationsByTypeAction($publicationType) {
        
        $rows = array();
        
        $publications = 
                Model\Data\Publication::getRowViewQueryObject()
                ->filterByType($publicationType)
                ->find();
        
        $columns = array('Titel', 'Erster Autor');
        $accessors = array('titleLink', 'repName');
        
        foreach ($publications as $pub) {

            
            /* @var $pub \DTA\MetadataBundle\Model\Data\Publication */
            $linkTarget = $this->generateUrl("Data_genericCreateOrEdit", array(
                    'className'=> $pub->getSpecializationClassName(),
                    'recordId'=>$pub->getId()
                )
            );

            $linkTo = function($href,$title){return '<a href="'.$href.'">'.$title.'</a>';};
            $rows[] = array(
                'titleLink' => $linkTo($linkTarget, $pub->getTitleString()),  // title
                'repName' => $pub->getFirstAuthorName(),                        // representative name
                'id'      => $pub->getId()                                  // id
            );
        }
        
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