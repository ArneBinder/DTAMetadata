<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * Base class for all domain controllers.
 */
class DTADomainController extends Controller {

    /**
     * The variable to indicate in the base template which domain to highlight in the main menu. */
    public static $domainKey = "";

    /**
     * The options in the second menu, displayed right under the main (domain switch) menu.
     */
    public $domainMenu = array();

    /**
     * Called by the _derived_ domain controllers. Automatically passes the domain key and menu of the derived class to the template.
     * @param $template Template to use for rendering, e.g. site specific as DTAMetadataBundle:DataDomain:index.html.twig
     * @param $options The data for the template to render 
     */
    public function renderWithDomainData($template, array $options = array(), Response $response = NULL) {

        // these are overriden by the calling subclass
        $defaultDomainMenu = array(
            'domainMenu' => $this->domainMenu,
            'domainKey' => $this->package,
        );

        // adds the domain menu to the options
        // adds the data for the view from $options
        $options = array_merge($defaultDomainMenu, $options);

        return $this->render($template, $options);
    }

}