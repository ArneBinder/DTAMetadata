<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Route prefix for all action routes.
 * Must match the package name.
 * @Route("/Classification")
 */
class ClassificationDomainController extends ORMController {

    /** @inheritdoc */
    public $package = "Classification";
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
        array("caption" => "Verwandte Werke", 'route' => 'home'),
    );

    /**
     * 
     * @return type
     * @Route("/ordnungssysteme/", name="conceptualDomain")
     */
    public function indexAction() {

        return $this->renderWithDomainData('DTAMetadataBundle:Package_Classification:index.html.twig', array(
        ));
    }

}
