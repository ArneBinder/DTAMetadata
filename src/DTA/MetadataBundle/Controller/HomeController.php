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
        array("caption" => "Aktuell in Bearbeitung", 'route' => 'home'),
        array("caption" => "Zuletzt Angesehen", 'route' => 'home'),
    );
    
    public function indexAction(Request $request) {
//        $p = new Model\Publication();
//        $p->setNumpages(101);
//        $p->save();
        
//        $ttq = Model\TasktypeQuery::create();
//        $root = $ttq->findRoot();
        
//        $tree = $this->retrieveSubtree($root);
//        doesn't work if foreign keys (tasks using this task type) impose integrity constraints.
//        $ttq->findOneById(5)->delete();

//        $personalName = Model\Data\PersonQuery::create()->findOneByGnd('119066882');
//        Model\Data\PersonalnameQuery::create()->filterByNamefragment($personalName->getRepresentativePersonalName()->getNamefragments())->find();
        
        return $this->renderWithDomainData('DTAMetadataBundle:Home:index.html.twig', array(
            'testData' => null
        ));
    }
}