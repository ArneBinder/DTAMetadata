<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DTA\MetadataBundle\Model;
use DTA\MetadataBundle\Form;

/**
 * Controls the functionality of the home page, e.g. the recently edited, viewed, created boxes.
 */
class HomeController extends DTADomainController {

    /** @inheritdoc */
    public $package = "Home";

    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Offene Tasks", 'route' => 'home'),
        array("caption" => "Aktuell in Bearbeitung", 'route' => 'home'),
        array("caption" => "Zuletzt Angesehen", 'route' => 'home'),
    );
    
    /**
     * 
     * @param \BaseObject
     * @param \Symfony\Component\Validator\Validator $validator
     * @return type
     */
//    private function validateRecursively($obj, $validator){
//        var_dump($validator->validate($obj));
//        $obj->
//        return $validator->validate($obj);
//    }
    
    public function indexAction(Request $request) {

        $lorenz = Model\Data\PersonQuery::create()->findOneById(300);
        
//        $publication = new Model\Data\Publication();
//        $publication->setNumpagesnumeric(0);
//        $title = new Model\Data\Title();
//        $title->addTitlefragment(Model\Data\Titlefragment::create(null, Model\Data\TitlefragmentPeer::TYPE_MAIN_TITLE));
//        $publication->setTitle($title);
//        
//        $book = new Model\Data\Book();
//        $book->setPublication($publication);
        
        /* @var $title DTA\MetadataBundle\Model\Data\Title */
//        $title = new Model\Data\Title();
//        $title->getpublic
                
        $q = Model\Data\TitleQuery::create()->usePublicationQuery()->filterByType(Model\Data\PublicationPeer::TYPE_CHAPTER)->endUse();
        
        return $this->renderWithDomainData('DTAMetadataBundle:Home:index.html.twig', array(
            'testData' => 
            $q->find()
//            array(
//                'chapterTitles' => $q->find(),
//                )
            //$title->getTitleFragments()->getFirst()
//               $authorsVolumes 
//            $outcome
//            $queryClass
//            $queryConstructionString
//             $groupA = Model\Workflow\TasktypeQuery::create()->orderByTreeLeft()->select('Name')->find()
//                array($publications->findOneById(17096)->getFirstAuthorName(), $publications->findOneById(16207)->getFirstAuthorName())
//            $tasks->findOneById(6016)->getPublicationgroup()->getName()
//             $groupA = Model\Data\PlaceQuery::create()->findOneByName("Berlin")->getPublications()
            //$p->getPersonalnames()
        ));
    }
}