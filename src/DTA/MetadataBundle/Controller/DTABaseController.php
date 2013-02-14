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
     * Returns the fully qualified class names to autoload and generate objects and work with them using their class names.
     * @param String $className The basic name of the class, all lower-case except the first letter (Work, Personalname, Namefragmenttype)
     */
    public function relatedClassNames($className){
        return array(
            "model"     => "DTA\\MetadataBundle\\Model\\" . $className,                 // the actual propel data
            "query"     => "DTA\\MetadataBundle\\Model\\" . $className . "Query",       // utility class for generating queries
            "peer"      => "DTA\\MetadataBundle\\Model\\" . $className . "Peer",        // utility class for reflection
            "formType"  => "DTA\\MetadataBundle\\Form\\Type\\" . $className . "Type",   // class for generating form inputs
        );
    }
    
    /**
     * 
     * @param type $className
     * @Route("/genericView/{className}")
     */
    public function genericViewAction($className){
        
        $classNames = $this->relatedClassNames($className);
        
        // for retrieving the entities
        $query = new $classNames['query'];
        // for retrieving the column names
        $peer = new $classNames['peer'];
        
        return $this->renderDomainSpecificAction("DTAMetadataBundle::genericView.html.twig", array(
            'data' => $query->find(),
            'columns' => $peer->getFieldNames(\BasePeer::TYPE_PHPNAME),
//            'peer' => $peer,
            'className' => $className,
        ));
        
    }
    
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
    public function genericEditFormAction($className, $recordId = 0) {

        $classNames = $this->relatedClassNames($className);
        
        $queryObject = $classNames['query']::create();

        // ------------------------------------------------------------------------
        // Try to fetch the object from the database to fill the form
        $record = $queryObject->findOneById($recordId);
        $obj = is_null($record) ? new $classNames['model'] : $record;

        $form = $this->createForm(new $classNames['formType'], $obj);
        return $form;
    }
    
    /**
     * Renders a generic form and returns the result for use in AJAX updating or creating database entities.
     * 
     * @param string $className   The name of the model class (e.g. Status, Title, Titlefragment)
     * @param int    $recordId    The id of the record to edit. Zero indicates that a new record shall be created.
     * @param string $captionProperty The property to use as caption for a select option (only for ajax use)
     * 
     * @Route("/plainForm/{className}/{recordId}/{captionProperty}", 
     *      name="plainForm", 
     *      defaults={"recordId"=0, "captionProperty"="Id"})
     */
    public function plainFormAction(Request $request, $className, $recordId = 0, $captionProperty = "Id"){
        
        $form = $this->genericEditFormAction($className, $recordId);
        
        return $this->render("DTAMetadataBundle::modalForm.html.twig", array(
            'form' => $form->createView(),
            'className' => $className,
            'captionProperty' => $captionProperty,
        ));
    }

    /**
     * Handles the POST request that is set off when clicking the submit button on a generic edit form.
     * @param type $Request
     * @param type $className
     * @param type $recordId
     * @Route("/genericUpdate/{className}/{recordId}", name="genericUpdate")
     */
    public function genericUpdateDatabaseAction(Request $request, $className, $recordId) {

        $form = $this->genericEditFormAction($className, $recordId);
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
     * @return HTML Option Element|nothing If the new action is called by a nested ajax form (selectOrAdd form type) the result is the option element to add to the nearby select.
     * 
     * @Route("/genericNew/{domainKey}/{className}/{captionProperty}", name="genericNew", defaults={"captionProperty"="Id"})
     */
    public function genericNewAction(Request $request, $className, $domainKey, $captionProperty = "Id") {

        $classNames = $this->relatedClassNames($className);
        
        // create object and its form
        $obj = new $classNames['model'];
        $form = $this->createForm(new $classNames['formType'], $obj);

        // save data on POST
        if ($request->isMethod("POST")) {
            $form->bind($request);
            if ($form->isValid()) {
                $obj->save();
            }
        }
        
        // AJAX case (nested form submit, no redirect)
        if("none" === $domainKey){
            
            // fetch data for the newly selectable option
            $id = $obj->getId();
            $caption = $obj->getByName($captionProperty);
            
            return new Response("<option value='$id'>$caption</option>");
        }
        // top level form submit case (redirect to view page)
        else{
            $route = implode(':', array('DTAMetadataBundle', $domainKey, 'index'));
            return $this->redirect($this->generateUrl($route));
        }
    }

    /**
     * Called by the _derived_ classes. Passes the domain key and menu of the derived class to the template given.
     * @param $template Template to use for rendering, e.g. site specific as DTAMetadataBundle:DataDomain:index.html.twig
     * @param $options The data for the template to render 
     */
    public function renderDomainSpecificAction($template, array $options = array()) {
        
        // these are overriden by the calling subclass
        $defaultDomainMenu = array(
            'domainMenu' => $this->domainMenu,
            "domainKey" => $this->domainKey);
        
        // replaces the domain menu of $defaultDomainMenu with the domain menu of options, if both are set.
        $options = array_merge($defaultDomainMenu, $options);
        return $this->render($template, $options);
    }

}