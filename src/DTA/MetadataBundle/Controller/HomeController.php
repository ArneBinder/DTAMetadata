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
    
    public function indexAction(Request $request) {

//        $lorenz = Model\Data\PersonQuery::create()->findOneById(300);
        
        return $this->renderWithDomainData('DTAMetadataBundle:Home:index.html.twig', array(
            'testData' => 
//            null
            array(
//                'multivol is root' => $multivolume->isRoot(),
//                'multivol scope'=>$multivolume->getScopeValue(),
//                'multivol parent'=>$multivolume->getParent(),
//                'volume is root'=>$volume->isRoot(),
//                'vol scope '=>$volume->getScopeValue(),
//                'root'=>Model\Data\PublicationQuery::create()->findRoot(),
//                'roots'=>Model\Data\PublicationQuery::create()->findRoots(),
                )
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