<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * The options in the second menu, displayed right under the main (domain selection) menu.
     * TODO: Generate this automatically. To avoid multiple edit locations on adding a new publication type
     * The inheritance should be detectable by the delegate behavior in the schema.xml
     */
    public $domainMenu = array();
    
    /**
     * Creates a form to EDIT AND CREATE any database entity.
     * It is inherited and called by the domain controllers (corresponding to 
     * the four main pages: Daten, Ordnungssysteme, Arbeitsfluss, Administration) 
     * to embed the form into their respective page structures.
     * @param string $className The name of the model class (see Model directory,
     * the DTA\MetadataBundle\Model namespace members or simply the schema.xml 
     * for sensible choices) to generate the form for. 
     * @param int recordId If the form shall be used for editing, the id of the entity.
     * Since 1 is the smallest ID given by propel, 0 indicates that a new object shall be created.
     */
    public function genericEditForm($className, $recordId = 0) {
        
        // The fully qualified class name (for autoloading)
        $objClassName = "DTA\\MetadataBundle\\Model\\".$className;
        
        // The form type is responsible for building the form
        $objFormTypeClassName = "DTA\\MetadataBundle\\Form\\Type\\".$className."Type";
        
        // The query type is used to obtain the object to edit from the database
        $objQueryClassName = "DTA\\MetadataBundle\\Model\\".$className."Query";
        $queryObject = $objQueryClassName::create();
        
        // ------------------------------------------------------------------------
        
        // Try to fetch the object from the database to fill the form
        $record = $queryObject->findOneById($recordId);
        $obj = is_null($record) ? new $objClassName : $record;

        $form = $this->createForm(new $objFormTypeClassName, $obj);
        return $form;
    }
    
    /**
     * @param type $Request
     * @param type $className
     * @param type $recordId
       @Route("/genericUpdate/{className}/{recordId}", name="genericUpdate")
     */
    public function genericUpdateDatabase(Request $request, $className, $recordId){
        
        $form = $this->genericEditForm($className, $recordId);
        $obj = $form->getData();
        
        if ($request->isMethod("POST")) {
            $form->bind($request);
            if($form->isValid()) $obj->save();
        }
    }

    public function renderWithDomainMenu($template, array $options = array()){
        $defaultDomainMenu = array('domainMenu' => $this->domainMenu, "domainKey" => $this->domainKey);
        // replaces the domain menu of $defaultDomainMenu with the one of options if both are set.
        $options = array_merge($defaultDomainMenu, $options); 
        return $this->render($template, $options);
    }
}
