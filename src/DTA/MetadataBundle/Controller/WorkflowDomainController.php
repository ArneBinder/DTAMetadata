<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Route prefix for all action routes, i.e. this page.
 * @Route("/arbeitsfluss")
 */
class WorkflowDomainController extends DTADomainController {

    /** @inheritdoc */
    public $package = "Workflow";

    /** @inheritdoc */
    public  $domainMenu = array(
        array("caption" => "Tasks", 'modelClass' => 'Task'),
        array("caption" => "Publikationsgruppen", 'modelClass' => 'Publicationgroup'),
        array("caption" => "Nachweise", 'modelClass' => 'Imagesource'),
        array("caption" => "Arbeitsschritte", 'modelClass' => 'Tasktype'),
        array("caption" => "Partner", 'modelClass' => 'Partner'),
        array("caption" => "Reporting", 'route' => 'reporting'),
    );

    /**
     * 
     * @return type
     * @Route("/", name="workflowDomain")
     */
    public function indexAction() {

        return $this->render('DTAMetadataBundle:WorkflowDomain:index.html.twig', array(
                ));
    }

    /**
     * @Route("/statistiken", name="reporting")
     */
    public function reportingAction() {
        return $this->render('DTAMetadataBundle:WorkflowDomain:reporting.html.twig');
    }

}
