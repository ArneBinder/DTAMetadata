<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * TODO: Nice to have: if the labels on the horizontal forms (column layout) would slide down the screen (position: fixed)
 * to support orientation in vertically long forms (where only the left border is visible)
 */

/**
 * Base class for all domain controllers. Contains generic actions (list all records, new record) 
 * that are derived from the database schema.
 */
class ORMController extends DTADomainController {

    // TODO: pressing enter in a modal form leads to a form submit and all data is lost, because the server redirects to the ajax response page
    
    /**
     * Returns the fully qualified class names to autoload and generate objects and work with them using their class names.
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

    /**
     * Renders a list of all entities of a certain type.
     * Takes into account how the list should be rendered according to the XML schemata,
     * where this can be specified using the table_row_view behavior.
     * @param string $package     the domain/object model package (Data, Classification, Workflow, Master)
     * @param string $className   
     * @Route(
     *      "{package}/showAll//{className}/{updatedObjectId}", 
     *      defaults = {"updatedObjectId" = 0},
     *      name = "genericView"
     * )
     */
    public function genericViewAllAction($package, $className, $updatedObjectId = 0) {

        $classNames = $this->relatedClassNames($package, $className);

        // for retrieving the entities
        $query = new $classNames['query'];
        
        // for retrieving the column names
        $modelClass = new $classNames["model"];

        return $this->renderDomainKeySpecificAction($package, "DTAMetadataBundle::genericView.html.twig", array(
                    'data' => $query->orderById()->find(),
                    'columns' => $modelClass::getTableViewColumnNames(),
                    'className' => $className,
                    'updatedObjectId' => $updatedObjectId,
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

    /**
     * Displays an edit form for a specific database entity.
     * Handles POST requests that have been set off due to editing a specific database entity.
     * @param type $domainKey
     * @param type $className
     * @param type $recordId
     * @Route("/showRecord/{domainKey}/{className}/{recordId}", name="viewRecord")
     */
    public function genericViewOneAction(Request $request, $domainKey, $className, $recordId) {

        // create object and its form
        $form = $this->generateForm($className, $recordId);

        // save data on POST
        if ($request->isMethod("POST")) {
            // put form data on a virtual form
            $form->bindRequest($request);
            if ($form->isValid()) {

                // parse propel object from virtual form
                $this->saveRecursively($form);

                $this->get('session')->getFlashBag()->add('success', 'Änderungen vorgenommen.');

                return $this->genericViewAllAction($domainKey, $className, $form->getData()->getId());
                
            } else {
                // TODO compare form_row (form_div_layout.html.twig) error reporting mechanisms to the overriden version of form_row (viewConfigurationForModels.html.twig)
                // and test whether they work on different inputs.
                var_dump($form->getErrors());
            }
        }
        return $this->renderDomainKeySpecificAction($domainKey, "DTAMetadataBundle:Form:genericEdit.html.twig", array(
                    'form' => $form->createView(),
                    'className' => $className,
                    'recordId' => $recordId,
                ));
    }
    
    /**
     * Deletes a record after a safety inquiry from the database.
     * @param type $domainKey   like in genericViewOneAction
     * @param type $className   like in genericViewOneAction
     * @param type $recordId    like in genericViewOneAction
     * @Route("/deleteRecord/{domainKey}/{className}/{recordId}", name="deleteRecord")
     */
    public function genericDeleteOneAction(Request $request, $domainKey, $className, $recordId) {

        // really delete data on affirmative POST
        if ($request->isMethod("POST")) {

            if( $request->get("reallyDelete") && $request->get('recordId') ){
                $classNames = $this->relatedClassNames($className);
                $query = new $classNames['query'];
                $record = $query->findOneById($recordId);
                if($record === null) throw new \Exception("The record ($className #$recordId) to be deleted doesn't exist.");
                $record->delete();
            };
            return $this->genericViewAllAction($domainKey, $className);
            
        } else {
            
            return $this->renderDomainKeySpecificAction($domainKey, "DTAMetadataBundle:ORM:confirmDelete.html.twig", array(
                'className' => $className,
                'recordId' => $recordId,
            ));
            
        }
        
        
//        return $this->renderDomainKeySpecificAction($domainKey, "DTAMetadataBundle:Form:genericEdit.html.twig", array(
//                    'form' => $form->createView(),
//                    'className' => $className,
//                    'recordId' => $recordId,
//                ));
    }

    /**
     * Creates a form to EDIT or CREATE any database entity.
     * @param string $className The name of the model class to create the form for (refer to the Model directory,
     * the DTA\MetadataBundle\Model namespace members) 
     * @param int recordId If the form shall be used for editing, the id of the entity to edit.
     * Since 1 is the first ID used by propel, 0 indicates that a new object shall be created.
     * @return The symfony form. If it is an edit form, with fetched data.
     */
    public function generateForm($className, $recordId = 0) {

        $classNames = $this->relatedClassNames($className);

        $queryObject = $classNames['query']::create();

        // ------------------------------------------------------------------------
        // Try to fetch the object from the database to fill the form
        $record = $queryObject->findOneById($recordId);
        $obj = is_null($record) ? new $classNames['model'] : $record;

        $form = $this->createForm(new $classNames['formType'], $obj);
        return $form;
//        return array('form'=>$form, 'object'=>$obj);
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
     * Handles POST requests that have been set off due to creating a new record.
     * @param string $className See genericEditForm for a parameter documentation.
     * @param string domainKey The domain where to redirect to, to view the created record. 
     *                          If it is set to "none", the database update is performed without redirecting (ajax case)
     * @param string captionProperty For ajax use: Which attribute does the select use to describe the entities it lists? Used to generate a new select option via ajax.
     * @return HTML Option Element|nothing If the new action is called by a nested ajax form (selectOrAdd form type) the result is the option element to add to the nearby select.
     * 
     * @Route("/genericNew/{domainKey}/{className}/{property}", name="genericNew", defaults={"property"="Id"})
     */
    public function genericNewAction(Request $request, $className, $domainKey, $property = "Id") {

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

            // AJAX case (nested form submit, no redirect)
            if ("ajax" == $domainKey) {
                
                // fetch data for the newly selectable option
                $id = $obj->getId();
                
                $captionAccessor = "get" . $property;
                
                // check whether the caption accessor function exists
                $cr = new \ReflectionClass("\DTA\MetadataBundle\Model\\" . $className);
                if( ! $cr->hasMethod($captionAccessor) )
                    throw new \Exception("Can't retrieve caption via $captionAccessor from $className object.");
                
                $caption = $obj->$captionAccessor();

                // return the new select option html fragment
                return new Response("<option value='$id'>$caption</option>");
            } else {
                // redirect to overview page on success
                $this->get('session')->getFlashBag()->add('success', 'Änderungen vorgenommen.');

                return $this->redirect($this->generateUrl('genericView', array(
                                    'domainKey' => $domainKey,
                                    'className' => $className,
                                    // highlight the changed or added entity in the list of all entities
                                    'updatedObjectId' => $form->getData()->getId(),
                                )));
            }
        }

        // render the form
        return $this->renderDomainKeySpecificAction($domainKey, 'DTAMetadataBundle:Form:genericNew.html.twig', array(
                    'form' => $form->createView(),
                    'className' => $className,
                ));
    }
    
    private function getControllerClassName($package) {
        return "DTA\\MetadataBundle\\Controller\\" . $package . "DomainController";
    }

    private function getModelReflectionClass($className) {
        return new \ReflectionClass("DTA\\MetadataBundle\\Model\\" . $className);
    }

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