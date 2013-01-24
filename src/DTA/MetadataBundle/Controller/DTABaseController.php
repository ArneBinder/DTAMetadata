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
     * The options in the second menu, displayed right under the main (domain switch) menu.
     * TODO: Generate this automatically. To avoid multiple edit locations on adding a new publication type
     * The inheritance should be detectable by the delegate behavior in the schema.xml
     */
    public $domainMenu = array();

    /**
     * Creates a form to EDIT AND CREATE any database entity.
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
     * Handles the POST request that is set off when clicking the submit button on a generic edit form.
     * @param type $Request
     * @param type $className
     * @param type $recordId
      @Route("/genericUpdate/{className}/{recordId}", name="genericUpdate")
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
     * @Route("/genericNew/{domainKey}/{className}", name="genericNew")
     */
    public function genericNewAction(Request $request, $className, $domainKey) {

//        var_dump($className);
        $objClassName = "DTA\\MetadataBundle\\Model\\" . $className;
        $objFormTypeName = "DTA\\MetadataBundle\\Form\\Type\\" . $className . "Type";
        $obj = new $objClassName;

//        $titlefragment = new Model\Titlefragment();
//        $titlefragment->setName("Fragmente einer Sprache der Liebe");
//        $titlefragment->setTitlefragmenttypeId(1);
//        $titlefragment->setRank(1);
//        $obj->addTitlefragment($titlefragment);
//        
//        $titlefragment2 = new Model\Titlefragment();
//        $titlefragment2->setName("Lyrische Exzerpte");
//        $titlefragment2->setTitlefragmenttypeId(2);
//        $titlefragment2->setRank(2);
//        $obj->addTitlefragment($titlefragment2);
//        
//        $obj->save();
//        
//        $titlefragment3 = new Model\Titlefragment();
//        $titlefragment3->setName("Another subtitle");
//        $titlefragment3->setTitlefragmenttypeId(2);
//        $titlefragment3->setRank(3);
//        $obj->addTitlefragment($titlefragment3);
//        $titlefragment = new Model\Titlefragment();
//        $obj->addTitlefragment($titlefragment);

        $form = $this->createForm(new $objFormTypeName, $obj);

        $bindTest = -1;

        if ($request->isMethod("POST")) {
            $form->bind($request);
            $bindTest = $form->getData(); //$obj->getTitlefragments();
//            $tf = new Model\Titlefragment();
            if ($form->isValid()) {
                $obj->save();
            }

//            $bindTest = $bindTest[0]->getTitleId();
        }

        
        
        // the logical template name DTAMetadataBundle:A:b resolves to src/DTA/MetadataBundle/Resources/views/A/b
//        return $this->renderDomainSpecific('DTAMetadataBundle::autoform.html.twig', array(
//                    'className' => $className,
//                    'persistedObject' => $bindTest,
//                    'form' => $form->createView(),
//                    'domainKey' => $domainKey,
//                ));
        
        return $this->redirect($this->generateUrl("home"));
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
