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
        array("caption" => "Korpora", "path" => 'home'),
        array("caption" => "Verwandte Werke", "path" => 'home'),
    );

    /**
     * 
     * @return type
     * @Route("/ordnungssysteme/", name="conceptualDomain")
     */
    public function indexAction() {

        $writ = new \DTA\MetadataBundle\Model\Writ();
        $writ->setNumpages(3);
        $writ->save();
        return $this->renderDomainSpecific('DTAMetadataBundle:ConceptualDomain:index.html.twig', array(
                    'persistedObject' => $writ,
                    ));
    }

}
