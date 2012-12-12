<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Route prefix for all action routes.
 * @Route("/daten")
 */
class DataDomainController extends DTABaseController {

    /** @inheritdoc */
    public $domainKey = "DataDomain";
    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Publikationen", "path" => 'home',
            "children" => array(
                array('caption' => "Alle Publikationsarten", 'path' => 'home'),
                array('caption' => "Bücher", 'modelClass' => 'Monograph'),
                array('caption' => "Zeitschriften", 'modelClass' => 'Magazine'),
                array('caption' => "Reihen", 'modelClass' => 'Series'),
                array('caption' => "Essays", 'modelClass' => 'Essay'))),
        array("caption" => "Personen", "path" => 'home',
            "children" => array(
                array('caption' => "Alle Personen", 'path' => 'home'),
                array('caption' => "Autoren", 'modelClass' => 'Author'),
                array('caption' => "Verleger", 'modelClass' => 'Publisher'),
                array('caption' => "Übersetzer", 'modelClass' => 'Translator'),
                array('caption' => "Drucker", 'modelClass' => 'Printer'))),
        array("caption" => "Verlage", 'modelClass' => 'PublishingCompany'),
    );

    /**
     * @Route("/", name="dataDomain")
     */
    public function indexAction() {
        return $this->renderWithDomainMenu('DTAMetadataBundle:DataDomain:index.html.twig');
    }

    /**
     * New action, embedded into the data domain page.
     * @param type $className
     * @Route("/neu/{className}", name="dataDomainNew")
     */
    public function newAction($className) {
        $form = $this->genericEditForm($className);
        return $this->renderWithDomainMenu('DTAMetadataBundle::autoform.html.twig', array(
                    'className' => $className,
                    'recordId' => 0, // create new record
                    'form' => $form->createView(),
                ));
    }
    
}
