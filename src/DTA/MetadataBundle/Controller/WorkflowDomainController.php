<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class WorkflowDomainController extends ORMController {

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

    public function indexAction() {

        return $this->renderWithDomainData('DTAMetadataBundle:Package_Workflow:index.html.twig', array(
                ));
    }

    public function reportingAction() {
        return $this->renderWithDomainData('DTAMetadataBundle:Package_Workflow:reporting.html.twig');
    }

}
