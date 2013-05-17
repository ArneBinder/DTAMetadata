<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Symfony\Component\Security\Core\SecurityContext;

class AdministrationDomainController extends DTABaseController {

    /** @inheritdoc */
    public static $domainKey = "AdministrationDomain";

    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Benutzer", 'modelClass' => 'DtaUser'),
    );

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

        return $this->render('DTAMetadataBundle::login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                )
        );
    }

    /**
     * @Route("/administration/", name="administrationDomain")
     */
    public function indexAction() {

        return $this->renderControllerSpecificAction('DTAMetadataBundle:AdministrationDomain:index.html.twig', array(
                    'hash' => 200
                ));
    }
    
    /**
     * Only for development purposes.
     * Keeps track of the ORM generated files that are associated to each model php class.
     * This is: A base class, containing the default logic.
     *          Peer class: For introspection
     *          Query class: For Retrieval
     *          Form type: For the view
     * @Route("/administration/sourceMonitor")
     */
    public function sourceMonitor(){
           
        $columns = array('name', 'filename', 'last change');
//        $ignoreList = array('.', '..', '.DS_STORE');
        
        $bundleDir = "../src/DTA/MetadataBundle/";
        
        $locations = array(
            'form' => "Form/Type/",
            'base' => "Model/om/",
            'map' => "Model/map/",
        );
        $modelLocation = "Model/";
        
        
        $workingDir = $bundleDir . $modelLocation;
        $handle = opendir($workingDir);
        if (!$handle) { throw new Exception("Problems opening $bundleDir"); }
        
        // the model directory contains all basic entities and their query and peer classes.
        // 1. FIND ALL BASIC ENTITIES BY IGNORING THE PEER AND QUERY CLASSES.
        $entities = array();
        while (false !== ($entry = readdir($handle)) ){
            
            // ignore non php files
            if( 0 !== substr_compare($entry, ".php", -strlen(".php"))) continue;
            //            if( false !== array_search($entry, $ignoreList, true) ) continue;
                    
            // ignore Query and Peer classes, denoted by the suffixes Peer and Query
            if( 0 === substr_compare($entry, "Peer.php", -strlen("Peer.php")) ) continue;
            if( 0 === substr_compare($entry, "Query.php", -strlen("Query.php")) ) continue;
            
            $entities[] = array(
                "filename" => $entry , 
                "name" => substr( $entry, 0, strlen($entry)-strlen(".php") ), 
                'last change' => date("l j.n Y H:i", filectime($workingDir.$entry)),
            );
        }
        
        // 2. CHECK WHETHER THERE ARE RELATED CLASSES THAT ARE UNNECESSARY
        // that is the base classes, the database maps, the query and peer classes and form types for 
        // entities that are not listed in the model directory.
        $entityFiles = array_map(function($e){return $e['filename'];}, $entities);
        $entityNames = array_map(function($e){return $e['name'];}, $entities);
            
        $strayClasses = array();
        foreach ($locations as $type => $path) {
            $handle = opendir($bundleDir . $path);
            $strayClasses[$type] = array();
            while (false !== ($entry = readdir($handle)) ){

                // ignore non php files
                if( 0 !== substr_compare($entry, ".php", -strlen(".php"))) continue;

                $baseClassFor = $entry;

                // BASE CLASSES
                
                // remove the prefix Base if it exists
                if( 0 === substr_compare($baseClassFor, "Base", 0, strlen("Base")) )
                    $baseClassFor = substr($baseClassFor, strlen("Base"));
                
                // remove the suffix Peer.php if it exists
                if( 0 === substr_compare($baseClassFor, "Peer.php", -strlen("Peer.php")) )
                    $baseClassFor = substr($baseClassFor, 0, -strlen("Peer.php"));
                
                // remove the suffix Query.php if it exists
                if( 0 === substr_compare($baseClassFor, "Query.php", -strlen("Query.php")) )
                    $baseClassFor = substr($baseClassFor, 0, -strlen("Query.php"));

                // TABLE MAPS
                
                // remove the suffix TableMap.php if it exists
                if( 0 === substr_compare($baseClassFor, "TableMap.php", -strlen("TableMap.php")) )
                    $baseClassFor = substr($baseClassFor, 0, -strlen("TableMap.php"));
                
                // FORM TYPES
                
                // remove the suffix Type.php if it exists
                if( 0 === substr_compare($baseClassFor, "Type.php", -strlen("Type.php")) )
                    $baseClassFor = substr($baseClassFor, 0, -strlen("Type.php"));
                
                if( false === array_search($baseClassFor, $entityNames, true) &&
                    false === array_search($baseClassFor, $entityFiles, true) ){
                    $strayClasses[$type][] = $entry;
                }

            }
        }
        
        return $this->renderControllerSpecificAction('DTAMetadataBundle:AdministrationDomain:sourceMonitor.html.twig', array(
                'columns' => $columns,
                'entries' => $entities,
                'strayTypes' => array('base', 'map', 'form'),
                'strayClasses' => $strayClasses,
            
        ));
    }

}
