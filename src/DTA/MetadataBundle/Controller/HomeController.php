<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DTA\MetadataBundle\Model;
use DTA\MetadataBundle\Form;

/**
 * Controls the functionality of the home page, e.g. the recently edited, viewed, created boxes.
 */
class HomeController extends ORMController {

    /** @inheritdoc */
    public $package = "Home";

    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Offene Tasks", 'route' => 'home'),
        array("caption" => "Aktuell in Bearbeitung", 'route' => 'home'),
        array("caption" => "Zuletzt Angesehen", 'route' => 'home'),
    );
    
    public function indexAction(Request $request) {

//        $persons = Model\Data\PersonQuery::create()
//                ->limit(10)
//                ->find();
//
//        $publications = array();
//        $multiVolumes = array();
//        
//        foreach($persons as $person){
//            
//            // find publications with identical titles
//            $publications = Model\Data\PublicationQuery::create()
//                    ->usePersonPublicationQuery()
//                        ->filterByPerson($person)
//                    ->endUse()
//                    ->find();
//            $publicationsByTitle = array();
//            foreach($publications as $publication){
//                $title = $publication->getTitle()->__toString();
//                $publicationsByTitle[$title][] = $publication;
//            }
//            
//            // aggregate into multivolumes
//            foreach($publicationsByTitle as $title => $aggregationData){
//                $multiVolumes[$title] = $aggregationData;
//            }
//            
//        }

        $lorenz = Model\Data\PersonQuery::create()->findOneById(300);
        $pub = Model\Data\PublicationQuery::create()->findOneById(16846);
        
        return $this->renderWithDomainData('DTAMetadataBundle:Home:index.html.twig', array(
            'testData' => 
//            null
            //$title->getTitleFragments()->getFirst()
            $lorenz->getAuthorIndex($pub)
                
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