<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdministrationDomainController extends DTABaseController {

    /** @inheritdoc */
    public static $domainKey = "AdministrationDomain";

    /** @inheritdoc */
    public static $domainMenu = array(
        array("caption" => "Benutzer", 'modelClass' => 'User'),
    );

    /**
     * 
     * @return type
     * @Route("/daten/", name="dataDomain")
     */
    public function indexAction() {

        return $this->renderControllerSpecificAction('DTAMetadataBundle:AdministrationDomain:index.html.twig', array(
                    'hash' => 200
                ));
    }

}
