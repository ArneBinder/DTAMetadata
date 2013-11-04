<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class WorkflowDomainController extends ORMController {

    /** @inheritdoc */
    public $package = "Workflow";

    /** @inheritdoc */
    public  $domainMenu = array(
        array("caption" => "Workflows", 'route' => 'Workflow_tasktypeViewAll'),
        array("caption" => "Tasks", 'modelClass' => 'Task'),
        array("caption" => "Publikationsgruppen", 'modelClass' => 'Publicationgroup'),
        array("caption" => "Partner", 'modelClass' => 'Partner'),
        array("caption" => "Lizenztypen", 'modelClass' => 'License'),
        array("caption" => "Reporting", 'route' => 'reporting'),
//        array("caption" => "Bezugsquellen", 'modelClass' => 'CopyLocation'),
    );

    public function indexAction() {

        return $this->renderWithDomainData('DTAMetadataBundle:Package_Workflow:index.html.twig', array(
                ));
    }
    
    public function tasktypeViewAllAction($package, $updatedObjectId = 0) {
        
        $className="Tasktype";
        $classNames = $this->relatedClassNames($package, $className);

        // for retrieving the entities
        $query = \DTA\MetadataBundle\Model\Workflow\TasktypeQuery::create();
        
        // for retrieving the column names
        $modelClass = new $classNames["model"];
        
        $records = $query
                ->filterByTreeLevel(array('min'=>1))
                ->orderByTreeLeft()
                ->find();
        
        return $this->renderWithDomainData("DTAMetadataBundle:Package_Workflow:tasktypeViewAll.html.twig", array(
                    'title' => 'Arbeitsschritte der verschiedenen Workflows',
                    'className' => $className,
                    'columns' => $modelClass::getTableViewColumnNames(),
                    'data' => $records,
                    'updatedObjectId' => $updatedObjectId,
                ));
    }

    public function reportingAction() {
        return $this->renderWithDomainData('DTAMetadataBundle:Package_Workflow:reporting.html.twig');
    }

}
