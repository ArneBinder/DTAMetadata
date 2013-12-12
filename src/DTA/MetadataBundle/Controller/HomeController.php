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

//        $s = new Model\Master\SequenceEntry();
//        $s->setSequencetype(Model\Master\SequenceEntryPeer::SEQUENCETYPE_SERIES)
//                ->setPublicationId(17001);
//        $s->save();
        
//        $se = Model\Data\PublicationQuery::create()->findOneById(17000)->getSequenceEntries()->getFirst();
//        $se2 = Model\Master\SequenceEntryQuery::create()->findList($se->getScopeValue());

//        try {
//            $title = new Model\Data\Title();
//            $title->save();
//            $fragment = new Model\Data\Titlefragment("Kurztitel", "Was ich werden wollte.");
//            $fragment->setSortableRank(2);
//            $fragment->setTitle($title);
//            $publication = new Model\Data\Publication();
//            $publication->setTitle($title)->save();
//            
//        } catch (\PropelException $exc) {
//            echo $exc->getTraceAsString();
//        }

        
//                "DTA\\MetadataBundle\\Model\\$package\\" . $className,
//        $rc = new \ReflectionClass('DTA\MetadataBundle\Model\Data\Publication');
//        $tableRowViewCaptions = $rc->getStaticPropertyValue("tableRowViewCaptions");

        
//        $fpp = $p->getPersonPublications()[0];
//        $fpn = $fpp->getPerson()->getPersonalnames()[0];
        
//        $accessor = $this->tableRowViewAccessors[$columnName];
//        $result = $this->getByName($accessor, \BasePeer::TYPE_PHPNAME);

//        $fpn = new Model\Master\PersonPublication();
        
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
            'testData' => 
//            $se2
//            $queryClass
//            $queryConstructionString
             NULL
            //$p->getPersonalnames()
        ));
    }
}