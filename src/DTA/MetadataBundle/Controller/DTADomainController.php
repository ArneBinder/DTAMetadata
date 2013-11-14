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
     * Indicates which model classes are handled by this domain. 
     * Equals one of the namespaces defined in the xml schemata (Classification, Data, Master, Workflow) */
    public $package = null;

    /**
     * The options in the lower menu, displayed under the main menu.
     */
    public $domainMenu = array();

    /**
     * Called by the inheriting domain controllers. Automatically passes the model package name and domain menu of the derived class to the template.
     * @param $template Template to use for rendering, e.g. site specific as DTAMetadataBundle:Package_Data:index.html.twig
     * @param $options The data for the template to render 
     */
    public function renderWithDomainData($template, array $options = array(), Response $response = NULL) {

        // these are overriden by the calling subclass
        $defaultDomainMenu = array(
            'package' => $this->package,
            'domainMenu' => $this->domainMenu,
        );

        // adds the domain menu to the options
        // adds the data for the view from $options
        $options = array_merge($defaultDomainMenu, $options);

        return $this->render($template, $options);
    }
    
    public function deliverCSV($template, $options = array()){
        $response = $this->render($template, $options);
        
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Description', 'Submissions Export');
        $response->headers->set('Content-Disposition', 'attachment; filename=duplicates.csv');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        $response->sendHeaders();
        $response->sendContent();

        return $response; 
    }
    /**
     * Short cuts for adding session messages that report the state of some transaction.
     * @param String $message The message to display
     */
    public function addErrorFlash($message){
        $this->get('session')->getFlashBag()->add('error', $message);
    }
    public function addSuccessFlash($message){
        $this->get('session')->getFlashBag()->add('success', $message);
    }
    public function addWarningFlash($message){
        $this->get('session')->getFlashBag()->add('warning', $message);
    }

}