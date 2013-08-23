<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Component\HttpFoundation\Response;

/**
 * TODO: Nice to have: if the labels on the horizontal forms (column layout) would slide down the screen (position: fixed)
 * to support orientation in vertically long forms (where only the left border is visible)
 */

/**
 * Base class for all domain controllers. Contains generic actions (list all records, new record) 
 * that are derived from the database schema.
 */
class ORMController extends DTADomainController {

    public $package = null;
    
    // TODO: pressing enter in a modal form leads to a form submit and all data is lost, 
    // because the server redirects to the ajax response page
    
    /**
     * Returns the fully qualified class names for generic loading.
     * @param String $package   The namespace/package name of the class (Data/Workflow/Classification/Master)
     * @param String $className The basic name of the class, all lower-case except the first letter (Work, Personalname, Namefragmenttype)
     */
    private function relatedClassNames($package, $className) {
        return array(
            "model"     => "DTA\\MetadataBundle\\Model\\$package\\" . $className,             // the actual propel active record
            "query"     => "DTA\\MetadataBundle\\Model\\$package\\" . $className . "Query",   // utility class for generating queries
            "peer"      => "DTA\\MetadataBundle\\Model\\$package\\" . $className . "Peer",    // utility class for reflection
            "formType"  => "DTA\\MetadataBundle\\Form\\$package\\" . $className . "Type",     // class for generating form inputs
        );
    }
    
    private function getControllerClassName($package) {
        return "DTA\\MetadataBundle\\Controller\\" . $package . "DomainController";
    }
    
    /**
     * Routes a request to specialized methods.
     * This is necessary since routes are parsed from annotations, and these are not inherited to the controllers.
     * So the only route that exists is that in the base class, causing each request to end up there.
     * The base class then calls the according method, that a specialized class has inherited from it.
     * @param type $package             Controller to address
     * @param type $methodName          Method to call
     * @param type $methodParameters    Parameters to pass to the call
     */
    private function useSpecializedImplementation($package, $methodName, $methodParameters){
        
        // get right controller for that package
        $controllerName = $this->getControllerClassName($package);
        $controller = new $controllerName;
        
        // setting the container is crucial for all kinds of things to work
        $controller->setContainer($this->container);
        
        return call_user_func_array( array($controller, $methodName), $methodParameters );
    }

    /**
     * Deletes a record from the database after having asked for a confirmation.
     * @param type $package   like in genericViewOneAction
     * @param type $className   like in genericViewOneAction
     * @param type $recordId    like in genericViewOneAction
     * @Route(
     *      "/{package}/delete/{className}/{recordId}", 
     *      name="deleteRecord"
     * )
     */
    public function genericDeleteOneAction(Request $request, $package, $className, $recordId) {

        // get confirmation
        if ( ! $request->isMethod("POST")) {

            return $this->renderWithDomainData("DTAMetadataBundle:ORM:confirmDelete.html.twig", array(
                'className' => $className,
                'recordId' => $recordId,
            ));
        
        // try to really delete data on confirm POST
        } else {
            
            // confirmed
            if( $request->get("reallyDelete") ){                                    // && $request->get('recordId')
                
                $classNames = $this->relatedClassNames($className);
                $query = new $classNames['query'];
                $record = $query->findOneById($recordId);
                
                if($record === null) 
                    $this->addErrorFlash("The record ($className #$recordId) to be deleted doesn't exist.");
                else{
                    $record->delete();
                    $this->addSuccessFlash("Datensatz gelöscht.");
                }
                
            // abort
            } else {
                
                if( ! $request->get("reallyDelete") )
                    $this->addWarningFlash("Datensatz nicht gelöscht.");
                else if(! $request->get('recordId'))
                    $this->addErrorFlash("Datensatz kann nicht gelöscht werden: Er existiert nicht.");
                
            }
            
            return $this->genericViewAllAction($package, $className);
            
        }
    }

    /**
     * Renders a single entity.
     * @param string $package      domain/object model package (Data, Classification, Workflow, Master)
     * @param string $className    model class
     * @param int    $recordId     
     * @Route(
     *      "{package}/show/{className}/{recordId}", 
     *      name = "viewRecord"
     * )
     */
    public function genericViewAction($package, $className, $recordId) {
        
        if($this->package === null){ // called through a HTTP request, not from another controller
            return $this->useSpecializedImplementation($package, __METHOD__, array('package'=>$package, 'className'=>$className, 'recordId' => $recordId));
        }
        
        $classNames = $this->relatedClassNames($package, $className);

        // for retrieving the entities
        $query = new $classNames['query'];
        
        $records = $query->findOneById($recordId);
        
        return $this->renderWithDomainData("DTAMetadataBundle:ORM:genericViewOne.html.twig", array(
                    'className' => $className,
                    'data' => $records,
                ));
    }
    
    /**
     * Renders a list of all entities of a certain type.
     * Takes into account how the list should be rendered according to the XML schema.
     * In the schema this can be specified using the table_row_view behavior.
     * @param string $package      domain/object model package (Data, Classification, Workflow, Master)
     * @param string $className    model class
     * @Route(
     *      "{package}/showAll/{className}", 
     *      name = "genericView"
     * )
     */
    public function genericViewAllAction($package, $className, $updatedObjectId = 0) {
        
        if($this->package === null){ // called through a HTTP request, not from another controller
            return $this->useSpecializedImplementation($package, __METHOD__, array('package'=>$package, 'className'=>$className, 'updatedObjectId' => $updatedObjectId));
        }
        
        $classNames = $this->relatedClassNames($package, $className);

        // for retrieving the entities
        $query = new $classNames['query'];
        
        // for retrieving the column names
        $modelClass = new $classNames["model"];
        
        $records = $query->orderById()->find();
        
        return $this->renderWithDomainData("DTAMetadataBundle:ORM:genericView.html.twig", array(
                    'className' => $className,
                    'columns' => $modelClass::getTableViewColumnNames(),
                    'data' => $records,
                    'updatedObjectId' => $updatedObjectId,
                ));
    }

    /**
     * Core logic for creating/editing entities. Database logic and form creation.
     * Can be reused in the domain controllers and customized by passing additional options.
     * Also handles the POST HTTP requests that have been set off by the form.
     * @param array     $entity             Associative array with the parameters that identify a record: package, className, recordId
     * @param function  $preSaveLogic       A closure that performs additional actions before saving.
     * @param array     $formTypeOptions    Options to influence the mapping of the object to a form 
     * 
     * @return array    Contains two fields, transaction (either 'edit', 'create', 'complete' or 'recordNotFound') which indicates what happened and 
     *                  depending on the transaction result a recordId of the created/updated record or the form.
     */
    protected function genericCreateOrEdit(
            Request $request, 
            $entity, 
            $preSaveLogic, 
            $formTypeOptions = array()) {
        
        // TODO compare form_row (form_div_layout.html.twig) error reporting mechanisms to the overriden version of form_row (viewConfigurationForModels.html.twig)
        // and test whether they work on different inputs.
        
        $package = $entity['package'];
        $className = $entity['className'];
        $recordId = $entity['recordId'];
        
        $classNames = $this->relatedClassNames($package, $className);
        
        if($recordId == 0){
            
            // create new object from class name
            $obj = new $classNames['model'];
            
        } else {
            
            // fetch the object from the database
            $queryObject = $classNames['query']::create();
            $obj = $queryObject->findOneById($recordId);
            if( is_null($obj) ){
                return array('transaction'=>'recordNotFound');
            }
            
        }

//        print_r($classNames);
        $form = $this->createForm(new $classNames['formType'], $obj, $formTypeOptions);

        // handle form submission
        if ($request->isMethod("POST")) {
            
            $form->handleRequest($request);

            // symfony validation: required fields etc.
            if ($form->isValid()) {

                // propel validation: unique constraints etc.
                if ($obj->validate()) {

                    // user defined pre save logic closure.
                    if(is_object($preSaveLogic) && ($preSaveLogic instanceof Closure)){
                        $preSaveLogic();
                    }
                    
//                  $this->saveRecursively($form);
                    $obj->save();
                    
                    // return edited/created entity ID as transaction success receipt
                    return array(
                        'transaction'=>'complete', 
                        'recordId'=>$form->getData()->getId()
                    );
                    
                } else { // propel validation fails

                    // add propel validation messages to flash bag
                    foreach ($obj->getValidationFailures() as $failure) {
                        $this->addErrorFlash($failure->getMessage());
                    }
                }
            } else { // symfony form validation fails

                // add symfony validation messages to flash bag
                foreach ($form->getErrors() as $error) {
                    $this->addErrorFlash($error->getMessage());
                }
            }
        }
        
        return array(
            'transaction'   => $recordId == 0 ? 'create' : 'edit', 
            'form'          => $form
        );
        
    }

    /**
     * Handles requests to generic create/edit request to any model class.
     * If specialized handlers exist in the domain controllers (that match the same route pattern)
     * these will be preferred by the router.
     * @param String    $package            The namespace/package name (Data/Workflow/Classification/Master)
     * @param String    $className          The model class name see src/DTA/MetadataBundle/Model/<$package>/ for classes
     * @param int       $recordId           The id of the entity to edit, 0 if new 
     * 
     * @Route(
     *      "{package}/edit/{className}/{recordId}", 
     *      name = "genericCreateOrEdit", 
     *      defaults = {"recordId"=0}
     * )
     */
    public function genericCreateOrEditAction(Request $request, $package, $className, $recordId) {
        
        if($this->package === null){
            return $this->useSpecializedImplementation($package, __METHOD__, array('request'=>$request, 'package'=>$package, 'className'=>$className, 'recordId' => $recordId));
        }
        
        $result = $this->genericCreateOrEdit(
                $request, 
                array(
                    'package'   => $package, 
                    'className' => $className, 
                    'recordId'  => $recordId), 
                function(){},
                array()
        );

        switch( $result['transaction'] ){
            case "recordNotFound":
                $this->addErrorFlash("Der gewünschte Datensatz kann nicht bearbeitet werden, weil er nicht gefunden wurde.");
                return $this->genericViewAllAction($package, $className, $result['recordId']);
            case "complete":
                $this->addSuccessFlash("Änderungen vorgenommen.");
                return $this->genericViewAllAction($package, $className, $result['recordId']);
            case "edit":
            case "create":
                return $this->renderWithDomainData("DTAMetadataBundle:Form:genericForm.html.twig", array(
                    'form' => $result['form']->createView(),
                    'transaction' => $result['transaction'],    // whether the form is for edit or create
                    'className' => $className,
                    'recordId' => $recordId,
                ));
        }
    }
    
    /**
     * Renders the form for the model without any surrounding elements. 
     * Used via AJAX to update or create database entities.
     * 
     * @param string $className   The name of the model class (e.g. Publication, Title, Titlefragment)
     * @param int    $recordId    The id of the record to edit. Zero indicates that a new record shall be created (since one is the smallest id)
     * @param string $property The property to use as caption for a select option (only for ajax use)
     * 
     * @Route("/ajaxModalForm/{className}/{recordId}/{property}", 
     *      name="ajaxModalForm", 
     *      defaults={"recordId"=0, "property"="Id"})
     */
    public function generateAjaxModalFormAction($className, $recordId = 0, $property = "Id") {

        $form = $this->generateForm($className, $recordId);

        // plain ajax response, html form wrapped in twitter bootstrap modal markup
        return $this->render("DTAMetadataBundle:Form:ajaxModalForm.html.twig", array(
                    'form' => $form->createView(),
                    'newActionParameters' => array(
                        'domainKey' => 'ajax',
                        'className' => $className,
                        'property' => $property,
                    ),
                ));
    }

    
    
    /**
     * Visits recursively all nested form elements and saves them.
     * @param Form $form The form object that contains the data defined by the top level form type (PersonType, NamefragmentType, ...)
     */
    private function saveRecursively(\Symfony\Component\Form\Form $form) {

        $entity = $form->getData();
        if (is_object($entity)) {
            $rc = new \ReflectionClass($entity);
            if ($rc->hasMethod('save'))
                $entity->save();
        }

        foreach ($form->getChildren() as $child) {
            $this->saveRecursively($child);
        }
    }
    
}

    /*
     * @param string captionProperty For ajax use: 
     *      Which attribute does the select use to describe the entities it lists? 
     *      Used to generate a new select option via ajax.
     * @return HTML Option Element|nothing 
     *      If the new action is called by a nested ajax form (selectOrAdd form type) the result is the option element to add to the nearby select.
     * @Route(
     *      "{package}/new/{className}/{property}", 
     *      name="genericNew", 
     *      defaults={"property"="Id"}
     * )
     */
//    public function genericNewAction(Request $request, $className, $domainKey, $property = "Id") {
//
//            // AJAX case (nested form submit, no redirect)
//            if ("ajax" == $domainKey) {
//                
//                // fetch data for the newly selectable option
//                $id = $obj->getId();
//                
//                $captionAccessor = "get" . $property;
//                
//                // check whether the caption accessor function exists
//                $cr = new \ReflectionClass("\DTA\MetadataBundle\Model\\" . $className);
//                if( ! $cr->hasMethod($captionAccessor) )
//                    throw new \Exception("Can't retrieve caption via $captionAccessor from $className object.");
//                
//                $caption = $obj->$captionAccessor();
//
//                // return the new select option html fragment
//                return new Response("<option value='$id'>$caption</option>");
//            } 
//        
//    }