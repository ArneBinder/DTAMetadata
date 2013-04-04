<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConceptualDomainController extends DTABaseController {

    /** @inheritdoc */
    public static $domainKey = "ConceptualDomain";
    /** @inheritdoc */
    public static $domainMenu = array(
        array("caption" => "Genres", 'route' => 'home',
            "children" => array(
                array('caption' => "DTA", 'route' => 'home'),
                array('caption' => "DWDS", 'route' => 'home'))),
        array("caption" => "Korpora", "modelClass" => 'Corpus'),
        array("caption" => "Verwandte Werke", 'route' => 'home'),
    );

    /**
     * 
     * @return type
     * @Route("/ordnungssysteme/", name="conceptualDomain")
     */
    public function indexAction() {

        return $this->renderControllerSpecificAction('DTAMetadataBundle:ConceptualDomain:index.html.twig', array(
        ));
    }

}
