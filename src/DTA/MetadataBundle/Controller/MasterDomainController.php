<?php

namespace DTA\MetadataBundle\Controller;

use \Symfony\Component\Security\Core\SecurityContext;
use \Symfony\Component\HttpFoundation\Request;
//DEBUG
use DTA\MetadataBundle\Model;
//DEBUG END
class MasterDomainController extends ORMController {

    /** @inheritdoc */
    public $package = "Master";

    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Benutzer", 'modelClass' => 'DtaUser'),
        array("caption" => "Arbeitsfluss", 'route' => 'workflowDomain'),
        array("caption" => "Ordnungssystem", 'route' => 'classificationDomain')
    );

    /**
     * Displays the login form for the entire application.
     */
    private $propelConnection;

    //DEBUG
    function dummy(){
        $this->propelConnection = \Propel::getConnection(Model\Master\DtaUserPeer::DATABASE_NAME);
        $start = microtime(true);
        $this->propelConnection->beginTransaction();
        $user = new Model\Master\DtaUser();
        // password encryption
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        $user->setSalt(md5(rand(-1239432, 23429304)));
        $saltedPassword = $encoder->encodePassword('$dta010', $user->getSalt());
        echo $saltedPassword;
        echo "XXX";
        echo $user->getSalt();
        $user->setUsername("Arne Binder")
            ->setPassword($saltedPassword)
            ->save($this->propelConnection);
        $this->propelConnection->commit();
        //$time_taken = microtime(true) - $start;
        //echo $task." ".$time_taken;
        //$this->messages[] = array("finished transaction ".$task=>$time_taken);
    }
    //DEBUG END

    public function loginFormAction() {
        //$this->dummy();
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
        
        // provide registered users as dropdown
        $uq = \DTA\MetadataBundle\Model\Master\DtaUserQuery::create()
                ->orderByUsername()
                ->find();

        return $this->renderWithDomainData('DTAMetadataBundle:Package_Master:login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                    'userNames' => $uq,
                )
        );
    }

    public function indexAction() {

        return $this->renderWithDomainData('DTAMetadataBundle:Package_Master:index.html.twig', array(
                    'hash' => 200
                ));
    }

    public function createOrEditUserAction(Request $request, $package, $className, $recordId) {

        $obj = $this->fetchOrCreate( array(
                'package'   => $package,
                'className' => $className,
                'recordId'  => $recordId)
        );

        $encrypter = null;
        //if($request->request->count() > 0){
            $params = implode(', ',$request->request->keys());
            $this->get('logger')->log('error','TEST logging '.$params);
            $dtauser = $request->request->get('dtauser');
            $this->get('logger')->log('error','$dtauser '.count($dtauser));
        //}

        //$this->get('logger')->log('error','TEST logging '.$request->request->count());
        //if($request->request->get('password')!=''){

        $encrypter = function(&$o){
            // password encryption
            $encoder = $this->get('security.encoder_factory')->getEncoder($o);
            //$o->setSalt(md5(rand(-1239432, 23429304)));
            $o->setSalt(100);
            $o->setPassword($encoder->encodePassword($o->getPassword(), $o->getSalt()));
        };
        //}
        $result = $this->genericCreateOrEdit($request, $obj, array(), $encrypter);
        return $this->handleResult($result, 'genericCreateOrEdit', $package, $className, $recordId);
    }
    
    /**
     * Clears the application cache 
     * @param type $request the http request wrapper
     * @param string Either "dev" or "prod" since the development mode and production mode have separate caches
     * Route is /Admin/ClearCache/{applicationMode}
     */
    public function clearCacheAction(Request $request, $applicationMode){
        
        // the application mode string is constrained via the parameter requirements in routing.yml
        // the route matches only /Admin/ClearCache/dev and  /Admin/ClearCache/prod
        $phpBinary = exec("which php");
        echo $phpBinary." -- ";
        $workingDirectory = exec("pwd");
        echo exec("whoami")." -- ";
//        echo implode(DIRECTORY_SEPARATOR, array($workingDirectory, '..', 'app', 'cache', 'dev'));
        $clearCacheCommand = "$phpBinary $workingDirectory/../app/console cache:clear";
        echo $clearCacheCommand;
        $result = exec($clearCacheCommand);
//        rmdir( implode(DIRECTORY_SEPARATOR, array($workingDirectory, '..', 'app', 'cache', 'dev')) . DIRECTORY_SEPARATOR);
//        echo $workingDirectory;
//        echo getcwd();
//        echo $applicationMode;
//        echo $result;
        return \Symfony\Component\HttpFoundation\Response::create($result);
        
    }
    
    /**
     * Only for development purposes.
     * Keeps track of the ORM generated files that are associated to each model php class.
     * This is: A base class, containing the default logic.
     *          Peer class: For introspection
     *          Query class: For Retrieval
     *          Form type: For the view
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
        if (!$handle) { throw new \Exception("Problems opening $bundleDir"); }
        
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
                'last change' => date("l j. F Y H:i", filectime($workingDir.$entry)),
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
        
        // 3. FIND BASIC ENTITIES WITHOUT FORM TYPE
        $handle = opendir($bundleDir . $locations['form']);
        // make a copy of classes without form
        $classesWithoutForm = $entityNames;
        while (false !== ($entry = readdir($handle)) ){

            // ignore non php files
            if( 0 !== substr_compare($entry, ".php", -strlen(".php"))) continue;

            $formFor = $entry;

            // remove the suffix Type.php if it exists
            if( 0 === substr_compare($formFor, "Type.php", -strlen("Type.php")) )
                $formFor = substr($formFor, 0, -strlen("Type.php"));

            if( false !== ($idx = array_search($formFor, $entityNames, true)) )
                unset($classesWithoutForm[$idx]);

        }
        
        
        return $this->renderWithDomainData('DTAMetadataBundle:Package_Master:sourceMonitor.html.twig', array(
                'columns' => $columns,
                'entries' => $entities,
                'strayTypes' => array('base', 'map', 'form'),
                'classesWithoutForm' => $classesWithoutForm,
                'strayClasses' => $strayClasses,
            
        ));
    }

}
