<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DataDomainController extends Controller
{
    public function indexAction()
    {
        $classMenu = array(
            array("caption"=>"Publikationen"),
            array("caption"=>"Personen"),
            array("caption"=>"Verlage"),
            array("caption"=>"Orte")
        );
        $writ = new \DTA\MetadataBundle\Model\Writ();
        $writ->setNumpages(3);
        $writ->save();
        return $this->render('DTAMetadataBundle:Data:index.html.twig', 
                array('persistedObject' => $writ,
                    'classMenu' => $classMenu));
    }
}
