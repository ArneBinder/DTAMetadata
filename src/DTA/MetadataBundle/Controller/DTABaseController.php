<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base class for all domain controllers. Contains generic actions (list all records, new record) 
 * that are derived from the database schema.
 */
class DTABaseController extends Controller {

    /**
     * IMPLEMENT ME IF CREATING A NEW DOMAIN 
     * The flag that it is set to true to indicate to the base template which domain to highlight in the main menu. */
    public $domainKey = "";

    /**
     * IMPLEMENT ME IF CREATING A NEW DOMAIN 
     * The options in the second menu, displayed right under the main (domain switch) menu.
     * TODO: Generate this automatically. To avoid multiple edit locations on adding a new publication type
     * The inheritance should be detectable by the delegate behavior in the schema.xml
     */
    public $domainMenu = array();

    /**
     * Creates a form to EDIT OR CREATE any database entity.
     * It is inherited and called by the domain controllers (corresponding to 
     * the four main pages: Daten, Ordnungssysteme, Arbeitsfluss, Administration) 
     * to embed the form into their respective page structures.
     * @param string $className The name of the model class to create the form for (refer to the Model directory,
     * the DTA\MetadataBundle\Model namespace members or simply the schema.xml) 
     * @param int recordId If the form shall be used for editing, the id of the entity.
     * Since 1 is the first ID used by propel, 0 indicates that a new object shall be created.
     * @return The symfony form. If it is an edit form, with fetched data.
     */
    public function genericEditForm($className, $recordId = 0) {

        // The fully qualified class name (for autoloading)
        $objClassName = "DTA\\MetadataBundle\\Model\\" . $className;

        // The form type is responsible for building the form
        $objFormTypeName = "DTA\\MetadataBundle\\Form\\Type\\" . $className . "Type";

        // The query type is used to obtain the object to edit from the database
        $objQueryClassName = "DTA\\MetadataBundle\\Model\\" . $className . "Query";
        $queryObject = $objQueryClassName::create();

        // ------------------------------------------------------------------------
        // Try to fetch the object from the database to fill the form
        $record = $queryObject->findOneById($recordId);
        $obj = is_null($record) ? new $objClassName : $record;

        $form = $this->createForm(new $objFormTypeName, $obj);
        return $form;
    }
    
    /**
     * Renders a generic form and returns the result for use in AJAX updating or creating database entities.
     * @param type $className   The name of the model class (e.g. Status, Title, Titlefragment)
     * @param type $recordId    The id of the record to edit. Zero indicates that a new record shall be created.
     * @Route("/plainForm/{className}/{recordId}", name="ajaxGenericForm")
     */
    public function genericEditFormView(Request $request, $className, $recordId = 0){
        
        $form = $this->genericEditForm($className, $recordId);
        
        return $this->render("DTAMetadataBundle::autoform.html.twig", array(
            'form' => $form->createView(),
            'className' => $className,
        ));
    }

    /**
     * Handles the POST request that is set off when clicking the submit button on a generic edit form.
     * @param type $Request
     * @param type $className
     * @param type $recordId
     * @Route("/genericUpdate/{className}/{recordId}", name="genericUpdate")
     */
    public function genericUpdateDatabase(Request $request, $className, $recordId) {

        $form = $this->genericEditForm($className, $recordId);
        $obj = $form->getData();

        if ($request->isMethod("POST")) {
            $form->bind($request);
            if ($form->isValid())
                $obj->save();
        }
    }

    /**
     * Handles POST requests that have been set off due to creating a new record.
     * @param string $className See genericEditForm for a parameter documentation.
     * @param string domainKey The domain where to redirect to, to view the created record. 
     *                          If it is set to "none", the database update is performed without redirecting (ajax case)
     * @Route("/genericNew/{domainKey}/{className}", name="genericNew")
     */
    public function genericNewAction(Request $request, $className, $domainKey = "none") {

//        var_dump($request);
        $objClassName = "DTA\\MetadataBundle\\Model\\" . $className;
        $objFormTypeName = "DTA\\MetadataBundle\\Form\\Type\\" . $className . "Type";
        $obj = new $objClassName;

        $form = $this->createForm(new $objFormTypeName, $obj);

        if ($request->isMethod("POST")) {
            $form->bind($request);
            if ($form->isValid()) {
                $obj->save();
            }
        }
        
        // the logical template name DTAMetadataBundle:A:b resolves to src/DTA/MetadataBundle/Resources/views/A/b
//        return $this->renderDomainSpecific('DTAMetadataBundle::autoform.html.twig', array(
//                    'className' => $className,
//                    'persistedObject' => $bindTest,
//                    'form' => $form->createView(),
//                    'domainKey' => $domainKey,
//                ));
        
        // AJAX case (nested form submit, no redirect)
        if("none" === $domainKey){
            return new Response("Ajax update successful.");
        }
        // top level form submit case (redirect to view page)
        else{
            $route = implode(':', array('DTAMetadataBundle', $domainKey, 'index'));
            return $this->forward($route);
        }
    }

    /**
     * Called by the _derived_ classes. Passes the domain key and menu of the derived class to the template given.
     * @param $template Template to use for rendering, e.g. site specific as DTAMetadataBundle:DataDomain:index.html.twig
     * @param $options The data for the template to render 
     */
    public function renderDomainSpecific($template, array $options = array()) {
        
        // these are overriden by the calling subclass
        $defaultDomainMenu = array(
            'domainMenu' => $this->domainMenu,
            "domainKey" => $this->domainKey);
        
        // replaces the domain menu of $defaultDomainMenu with the domain menu of options, if both are set.
        $options = array_merge($defaultDomainMenu, $options);
        return $this->render($template, $options);
    }

}
