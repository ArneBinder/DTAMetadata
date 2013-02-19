<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConceptualDomainController extends DTABaseController {

    /** @inheritdoc */
    public $domainKey = "ConceptualDomain";
    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Genres", "path" => 'home',
            "children" => array(
                array('caption' => "DTA", 'path' => 'home'),
                array('caption' => "DWDS", 'path' => 'home'))),
        array("caption" => "Korpora", "modelClass" => 'Corpus'),
        array("caption" => "Verwandte Werke", "path" => 'home'),
    );

    /**
     * 
     * @return type
     * @Route("/ordnungssysteme/", name="conceptualDomain")
     */
    public function indexAction() {

        return $this->renderDomainSpecificAction('DTAMetadataBundle:ConceptualDomain:ConceptualDomain.html.twig', array(
        ));
    }

}
