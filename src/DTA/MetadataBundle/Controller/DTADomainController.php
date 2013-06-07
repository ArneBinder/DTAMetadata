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
     * IMPLEMENT THIS IF CREATING A NEW DOMAIN 
     * The flag that it is set to true to indicate to the base template which domain to highlight in the main menu. */
    public static $domainKey = "";

    /**
     * IMPLEMENT THIS IF CREATING A NEW DOMAIN 
     * The options in the second menu, displayed right under the main (domain switch) menu.
     * TODO: Generate this automatically. To avoid multiple edit locations on adding a new publication type
     * The inheritance should be detectable by the delegate behavior in the schema.xml
     */
    public $domainMenu = array();

    public function renderDomainKeySpecificAction($domainKey, $template, array $options = array()) {

        $controllerName = $this->getControllerClassName($domainKey);
        
        $cr = new \ReflectionClass($controllerName);
        $controller = new $controllerName;
        
        // these are overriden by the calling subclass
        $defaultDomainMenu = array(
           'domainMenu' => $controller->domainMenu,
            "domainKey" => $cr->getStaticPropertyValue('domainKey'));

        // replaces the domain menu of $defaultDomainMenu with the domain menu of options, if both are set.
        $options = array_merge($defaultDomainMenu, $options);
        return $this->render($template, $options);
    }

    /**
     * Called by the _derived_ domain controllers. Automatically passes the domain key and menu of the derived class to the template.
     * @param $template Template to use for rendering, e.g. site specific as DTAMetadataBundle:DataDomain:index.html.twig
     * @param $options The data for the template to render 
     */
    public function renderControllerSpecificAction($template, array $options = array()) {

        // static properties cannot be accessed via $this
        $controllerReflection = new \ReflectionClass($this);

        // these are overriden by the calling subclass
        $defaultDomainMenu = array(
            'domainMenu' => $this->domainMenu,
            "domainKey" => $controllerReflection->getStaticPropertyValue('domainKey'));

        // replaces the domain menu of $defaultDomainMenu with the domain menu of options, if both are set.
        // adds the data for the view from $options
        $options = array_merge($defaultDomainMenu, $options);

        return $this->render($template, $options);
    }

}