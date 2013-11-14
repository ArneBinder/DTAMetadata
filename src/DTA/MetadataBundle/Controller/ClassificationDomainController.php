<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
        array("caption" => "Kategorien", "modelClass" => 'Category'),
        array("caption" => "Schlagworte", "modelClass" => 'Tag'),
        array("caption" => "Publikationsgruppen", 'route' => 'home'),
    );

    public function indexAction() {

        return $this->renderWithDomainData('DTAMetadataBundle:Package_Classification:index.html.twig', array(
        ));
    }

}
