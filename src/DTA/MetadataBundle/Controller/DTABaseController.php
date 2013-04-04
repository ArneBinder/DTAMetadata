<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use \Symfony\Component\Security\Core\SecurityContext;

/**
 * Base class for all domain controllers. Contains generic actions (list all records, new record) 
 * that are derived from the database schema.
 */
class DTABaseController extends Controller {

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
    public static $domainMenu = array();

    /**
     * Displays the login form for the entire application.
     * @Route("/Anmeldung", name="login")
     */
    public function loginFormAction() {

        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
                        'DTAMetadataBundle::login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                        )
        );
    }

    /**
     * Returns the fully qualified class names to autoload and generate objects and work with them using their class names.
     * @param String $className The basic name of the class, all lower-case except the first letter (Work, Personalname, Namefragmenttype)
     */
    public function relatedClassNames($className) {
        return array(
            "model" => "DTA\\MetadataBundle\\Model\\" . $className, // the actual propel active record
            "query" => "DTA\\MetadataBundle\\Model\\" . $className . "Query", // utility class for generating queries
            "peer" => "DTA\\MetadataBundle\\Model\\" . $className . "Peer", // utility class for reflection
            "formType" => "DTA\\MetadataBundle\\Form\\Type\\" . $className . "Type", // class for generating form inputs
        );
    }

    /**
     * 
     * @param type $className
     * @Route("/genericView/{domainKey}/{className}/{updatedObjectId}", 
     *      defaults={"updatedObjectId"=0},
     *      name="genericView")
     */
    public function genericViewAllAction($domainKey, $className, $updatedObjectId = 0) {

        $classNames = $this->relatedClassNames($className);

        // for retrieving the entities
        $query = new $classNames['query'];
        // for retrieving the column names
        $modelClass = new $classNames["model"];

//        $rc = new \ReflectionClass();
//        $rc->getStaticPropertyValue("")

        return $this->renderDomainKeySpecificAction($domainKey, "DTAMetadataBundle::genericView.html.twig", array(
                    'data' => $query->find(),
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
     * Used for creating an edit form for a specific database entity.
     * Handles POST requests that have been set off due to editing a specific database entity.
     * @param type $domainKey
     * @param type $className
     * @param type $recordId
     * @Route("/zeigeDatensatz/{domainKey}/{className}/{recordId}", name="viewRecord")
     */
    public function genericViewOneAction(Request $request, $domainKey, $className, $recordId) {

        // create object and its form
        $form = $this->dynamicForm($className, $recordId);

        // save data on POST
        if ($request->isMethod("POST")) {
            // put form data on a virtual form
            $form->bindRequest($request);
            if ($form->isValid()) {

                // parse propel object from virtual form
                $this->saveRecursively($form);

                $this->get('session')->getFlashBag()->add('success', 'Änderungen vorgenommen.');

                return $this->redirect($this->generateUrl('genericView', array(
                                    'domainKey' => $domainKey,
                                    'className' => $className,
                                    'updatedObjectId' => $form->getData()->getId(),
                                )));
            } else {
                // TODO compare form_row (form_div_layout.html.twig) error reporting mechanisms to the overriden version of form_row (formTypes.html.twig)
                // and test whether they work on different inputs.
                var_dump($form->getErrors());
            }
        }
        return $this->renderDomainKeySpecificAction($domainKey, "DTAMetadataBundle::formWrapper.html.twig", array(
                    'form' => $form->createView(),
                    'action' => 'edit',
                    'className' => $className,
                    'recordId' => $recordId,
                ));
    }

    /**
     * Creates a form to EDIT or CREATE any database entity.
     * @param string $className The name of the model class to create the form for (refer to the Model directory,
     * the DTA\MetadataBundle\Model namespace members) 
     * @param int recordId If the form shall be used for editing, the id of the entity to edit.
     * Since 1 is the first ID used by propel, 0 indicates that a new object shall be created.
     * @return The symfony form. If it is an edit form, with fetched data.
     */
    public function dynamicForm($className, $recordId = 0) {

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
     * Renders a dynamic form and returns the result for use in AJAX updating or creating database entities.
     * 
     * @param string $className   The name of the model class (e.g. Status, Title, Titlefragment)
     * @param int    $recordId    The id of the record to edit. Zero indicates that a new record shall be created.
     * @param string $captionProperty The property to use as caption for a select option (only for ajax use)
     * 
     * @Route("/ajaxModalForm/{className}/{recordId}/{captionProperty}", 
     *      name="ajaxModalForm", 
     *      defaults={"recordId"=0, "captionProperty"="Id"})
     */
    public function generateAjaxModalFormAction($className, $recordId = 0, $captionProperty = "Id") {

//        sleep(2);

        $form = $this->dynamicForm($className, $recordId);

        // plain ajax response, without any menus or other html
        return $this->render("DTAMetadataBundle::ajaxModalForm.html.twig", array(
                    'form' => $form->createView(),
                    'className' => $className,
                    'captionProperty' => $captionProperty,
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
     * @Route("/genericNew/{domainKey}/{className}/{captionProperty}", name="genericNew", defaults={"captionProperty"="getId"})
     */
    public function genericNewAction(Request $request, $className, $domainKey, $captionProperty = "getId") {

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
                $getter = 'get' . $captionProperty;
                $caption = $obj->$getter();

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
        return $this->renderDomainKeySpecificAction($domainKey, 'DTAMetadataBundle::formWrapper.html.twig', array(
                    'form' => $form->createView(),
                    'className' => $className,
                ));
    }

    private function getControllerReflectionClass($domainKey) {
        return new \ReflectionClass("DTA\\MetadataBundle\\Controller\\" . $domainKey . "Controller");
    }

    private function getModelReflectionClass($className) {
        return new \ReflectionClass("DTA\\MetadataBundle\\Model\\" . $className);
    }

    public function renderDomainKeySpecificAction($domainKey, $template, array $options = array()) {

        $cr = $this->getControllerReflectionClass($domainKey);

        // these are overriden by the calling subclass
        $defaultDomainMenu = array(
            'domainMenu' => $cr->getStaticPropertyValue('domainMenu'),
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
            'domainMenu' => $controllerReflection->getStaticPropertyValue('domainMenu'),
            "domainKey" => $controllerReflection->getStaticPropertyValue('domainKey'));

        // replaces the domain menu of $defaultDomainMenu with the domain menu of options, if both are set.
        // adds the data for the view from $options
        $options = array_merge($defaultDomainMenu, $options);

        return $this->render($template, $options);
    }

}