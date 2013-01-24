<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DTA\MetadataBundle\Model;
use DTA\MetadataBundle\Form;

/**
 * Controls the functionality of the home page, e.g. the recently edited, viewed, created boxes.
 */
class HomeController extends DTABaseController {

    /** @inheritdoc */
    public $domainKey = "Home";

    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Zuletzt Angelegt", "path" => 'home'),
        array("caption" => "Zuletzt Bearbeitet", "path" => 'home'),
        array("caption" => "Zuletzt Angesehen", "path" => 'home'),
    );

    /**
     * @return void
     * @Route("/home", name="home")
     */
    public function indexAction(Request $request) {
        return $this->renderDomainSpecific('DTAMetadataBundle:Home:Home.html.twig');
    }

    /**
     * @Route("/new/personalName", name="newPersonalName")
     */
    public function newPersonalNameAction(Request $request) {
        
//        $obj = Model\PersonalnameQuery::create()->findOneById(1);
        $obj = new Model\Personalname();
//        $firstFragment = new Model\Namefragment();
//        $obj->addNamefragment($firstFragment);
        
        $form = $this->createForm(new Form\Type\PersonalnameType(), $obj);
        $result = "GET request";
        if ($this->getRequest()->getMethod() == 'POST') {//$request->isMethod("POST")) {
            $form->bind($request);
            $result = $form->getErrorsAsString();

            if ($form->isValid()) {
                $obj->save();
                $result = "saved.";
            }
        }
        return $this->renderDomainSpecific('DTAMetadataBundle::autoform.html.twig', array(
                    'className' => 'Personalname',
                    'persistedObject' => $result,
                    'form' => $form->createView(),
                ));
    }
     /**
     * @Route("/new/title")
     */
    public function newTitleAction(Request $request) {

        $objClassName = "DTA\\MetadataBundle\\Model\\Title";
        $objFormClassName = "DTA\\MetadataBundle\\Form\\Type\\TitleType";
        $obj = new $objClassName;

        $form = $this->createForm(new $objFormClassName, $obj);

        if ($request->isMethod("POST")) {
            $form->bind($request);
            if ($form->isValid())
                $obj->save();
        }

        return $this->renderDomainSpecific('DTAMetadataBundle::autoform.html.twig', array(
                    'className' => 'Title',
                    'persistedObject' => 'persisted.',
                    'form' => $form->createView(),
                ));
    }
    
    /**
     * @Route("/new/{className}")
     */
    public function genericNewTestAction(Request $request, $className) {

        $objClassName = "DTA\\MetadataBundle\\Model\\$className";
        $objFormClassName = "DTA\\MetadataBundle\\Form\\Type\\".$className."Type";
        $obj = new $objClassName;

        $form = $this->createForm(new $objFormClassName, $obj);

        if ($request->isMethod("POST")) {
            $form->bind($request);
            if ($form->isValid())
                $obj->save();
        }

        $mono = new Model\Monograph();
        
//        $mono = new Model\Monograph();
//        $mono->num
        
        return $this->renderDomainSpecific('DTAMetadataBundle::autoform.html.twig', array(
                    'className' => $className,
                    'persistedObject' => 'persisted.',
                    'form' => $form->createView(),
                ));
    }
    

}


//    /**
//     * @Route("/new/nameFragment")
//     */
//    public function newNameFragmentAction(Request $request) {
//        $obj = new Model\Namefragment();
//        $form = $this->createForm(new Form\Type\NamefragmentType(), $obj);
//
//        if ($request->isMethod("POST")) {
//            $form->bind($request);
//            if ($form->isValid()) {
//                $obj->save();
//            }
//        }
//
//        return $this->renderDomainSpecific('DTAMetadataBundle::autoform.html.twig', array(
//                    'className' => "Namefragment",
//                    'persistedObject' => $obj,
//                    'form' => $form->createView(),
//                ));
//    }

