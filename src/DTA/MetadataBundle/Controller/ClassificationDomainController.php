<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ClassificationDomainController extends DTADomainController {

    /** @inheritdoc */
    public static $domainKey = "ConceptualDomain";
    /** @inheritdoc */
    public  $domainMenu = array(
//        array("caption" => "Genres", 'route' => 'home',
//            "children" => array(
//                array('caption' => "DTA", 'route' => 'home'),
//                array('caption' => "DWDS", 'route' => 'home'))),
        array("caption" => "Genres", "modelClass" => 'Genre'),
        array("caption" => "Korpora", "modelClass" => 'Corpus'),
        array("caption" => "Kategorien", "modelClass" => 'Category'),
        array("caption" => "Tags", "modelClass" => 'Tag'),
        array("caption" => "Schriftarten", "modelClass" => 'Font'),
        array("caption" => "Sprachen", "modelClass" => 'Language'),
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
