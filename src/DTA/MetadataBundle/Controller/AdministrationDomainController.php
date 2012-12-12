<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdministrationDomainController extends DTABaseController {
    /** @inheritdoc */
    public $domainKey = "AdministrationDomain";
    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Benutzer", "path" => 'home'),
    );

    /**
     * 
     * @return type
     * @Route("/daten/", name="dataDomain")
     */
    public function indexAction() {

        $writ = new \DTA\MetadataBundle\Model\Writ();
        $writ->setNumpages(3);
        $writ->save();
        return $this->renderWithDomainMenu('DTAMetadataBundle:AdministrationDomain:index.html.twig', array(
                    'persistedObject' => $writ,
                    ));
    }

}
