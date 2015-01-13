<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

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
     * Deletes a record from the database after having asked for a confirmation.
     * @param type $package   like in genericViewOneAction
     * @param type $className   like in genericViewOneAction
     * @param type $recordId    like in genericViewOneAction
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

                $classNames = $this->relatedClassNames($package, $className);
                $query = new $classNames['query'];
                $record = $query->findOneById($recordId);

                if($record === null){
                    $this->addErrorFlash("The record ($className #$recordId) to be deleted doesn't exist.");
                }
                else{
                    $record->delete();
                    $this->addSuccessFlash("Datensatz gelöscht.");
                }

            // abort
            } else {

                if( ! $request->get("reallyDelete") ){
                    $this->addWarningFlash("Datensatz nicht gelöscht.");
                } else if(! $request->get('recordId')){
                    $this->addErrorFlash("Datensatz kann nicht gelöscht werden: Er existiert nicht.");
                }

            }

            return $this->genericViewAllAction($package, $className);

        }
    }

    /**
     * Renders a single entity for ajax display (i.e. without any additional markup like menus or footers).
     * @param string $package      domain/object model package (Data, Classification, Workflow, Master)
     * @param string $className    model class
     * @param int    $recordId
     */
    public function genericViewAction(Request $request, $package, $className, $recordId) {

        $classNames = $this->relatedClassNames($package, $className);

        // for retrieving the entities
        $query    = new $classNames['query'];
//        $formType = new $classNames['formType'];

        $record = $query->findOneById($recordId);

        $form = $this->genericCreateOrEdit($request, $record);

//        return $this->renderWithDomainData("DTAMetadataBundle:ORM:createOrEdit.html.twig", array(
//                    'form' => $form['form']->createView(),
//                    'transaction' => $form['transaction'],    // whether the form is for edit or create
//                    'className' => $className,
//                    'recordId' => $recordId,
//                ));

        return $this->renderWithDomainData("DTAMetadataBundle:Form:genericBaseForm.html.twig", array(
                    'className' => $className,
                    'form' => $form['form']->createView(),
                ));
    }

    /**
     *
     */
    private static function extractPureAccessor($accessor){
        if(strncmp($accessor, "accessor:", strlen("accessor:"))) {
            return $accessor;
        }elseif(!strncmp($accessor, "accessor:get", strlen("accessor:get"))) {
            $modifiedAccessor = substr($accessor,strlen("accessor:get"));
            return $modifiedAccessor;
        }else{
            throw new InvalidArgumentException(sprintf(
                'Could not extract pure accessor of \'%s\'.', $accessor));
        }
    }

    protected function findPaginatedSortedFiltered($request, $package, $className, $hiddenIdColumn = false){

        $classNames = $this->relatedClassNames($package, $className);
        $modelClass = new $classNames["model"];
        $query = $modelClass::getRowViewQueryObject();

        //DEBUG
        //$this->get('logger')->critical("modelClass: ".$modelClass);
        $columns = $modelClass::getTableViewColumnNames();

        $temp = "";
        foreach ($columns as $column) {
            $accessor = $modelClass->tableRowViewAccessors[$column];
            $temp = $temp.$accessor.", ";
        }

        $this->get('logger')->critical("ORMcontroller accessors: ".$temp);
        $this->get('logger')->critical(urldecode($request));
        $this->get('logger')->critical("hiddenIdColumn: ".$hiddenIdColumn);
        //DEBUG END

        // SORTING
        if($request->get('order')) {
            $direction = $request->get('order')[0]['dir'];
            $orderColumn = $columns[$request->get('order')[0]['column'] - ($hiddenIdColumn?1:0)];
            $orderAccessor = 'orderBy'.ORMController::extractPureAccessor($modelClass->tableRowViewAccessors[$orderColumn]);

            // is the column embedded?
            $embeddedAccessor = explode('Of',$orderAccessor);
            if(count($embeddedAccessor)>1){
                $useQuery = "use$embeddedAccessor[1]Query";
                $orderAccessor = $embeddedAccessor[0];
                $query = $query->$useQuery();
            // is it a representative?
            }

            if(is_callable(array($query,$orderAccessor))){
                $this->get('logger')->critical("sort via: ".$orderAccessor." ".$direction);
                $query = $query->$orderAccessor($direction);
            }else{
                $this->get('logger')->critical("not callable: ".$orderAccessor);
            }
            // is the column embedded? Then Close it.
            if(count($embeddedAccessor)>1){
                $query = $query->endUse();
            }

        // use the class specific default sorting
        }elseif(method_exists($classNames['query'], 'sqlSort')){
                $query = $classNames['query']::sqlSort($query, \ModelCriteria::ASC);
        }
        // SORTING END

        $this->get('logger')->critical("sorted query: " . $query->toString());


        // pagination offset
        $offset = $request->get('start');
        $numRecords = $request->get('length');

        // filtering is more difficult than initially thought!
        // - using ILIKE, case insensitive search is performed
        // - using % and _ several and a single character can be wildcarded, respectively
        // - a union mechanism seems to be missing (search on each column and union the matches), could be simulated by appending the search results manually, but that destroys sorting
        //   another option might be to use criteria (the $query->where(...) methods) because there is an _or() function to use with them but it is not obvious how to formulate 
        //   expressions like the ones below in this syntax (especially if it comes to date specifications which are related by different columns)

        /* @var $query \DTA\MetadataBundle\Model\Data\BookQuery */
//        $result2 = $modelClass::getRowViewQueryObject()->usePublicationQuery()->useTitleQuery()->useTitlefragmentQuery()->filterByName('%lage%', \ModelCriteria::ILIKE)->endUse()->endUse()->endUse()->find();
//        $result1 = $modelClass::getRowViewQueryObject()->usePublicationQuery()->useTitleQuery()->useTitlefragmentQuery()->filterByName('%sinn%', \ModelCriteria::ILIKE)->endUse()->endUse()->endUse()->find();
//        /* @var $result1 \PropelObjectCollection */
//        foreach($result2 as $result)
//        $result1->append($result);
//        $query = $query->usePublicationQuery()->useDatespecificationRelatedByPublicationdateIdQuery()->filterByYear('1811')->endUse()->endUse();
//        $query = $query->wh // where('year = ?', 1811);





        $this->get('logger')->critical($query->find()->count());
        /* @var $query \DTA\MetadataBundle\Model\Data\PublicationQuery */
        $entities = $query->setFormatter(\ModelCriteria::FORMAT_ON_DEMAND)
                          ->offset($offset)
                          ->limit($numRecords)
                          ->find();

        return $entities;

//        } catch (\PropelException $exc) {
////            $query = $modelClass::getRowViewQueryObject();
//            $query->offset(NULL)->limit(NULL);
//            $allEntities = $query->find();
//            $entities = array();//array_slice($entities->toArray(), $offset, $numRecords);
//            for ($i = 0; $i < $numRecords; $i++) {
//                $entities[] = $allEntities->get($offset+$i);
//            }
//        }
    }

    /**
     *
     * @param type $entities Propel entities
     * @param type $columns  The attributes to display, needs to be a subset of the table row view defined columns
     * @param type $package  Data, Workflow, Classification or Master
     * @param type $className The unqualified class name of the entity
     * @return type array containing one array per record, listing the values of the given attributes (columns)
     */
    protected function formatAsArray($entities, $columns, $package, $className){
        $result = array();
        foreach($entities as $entity) {
            $row = array();
            for ($i = 0; $i < count($columns); $i++) {
                $column = $columns[$i];
                $attribute = $entity->getAttributeByTableViewColumName($column);
                if(is_object($attribute)){
                    $value = $attribute->__toString();
                } else {
                    $value = $attribute;
                }
                // add an edit button to the efirst column entry
                if($i === 0){
                    $editLink = $this->generateUrl($package . '_genericCreateOrEdit', array(
                        'package'=>$package, 'className'=>$className, 'recordId'=>$entity->getId()
                    ));
//                    $deleteLink = $this->generateUrl($package . '_deleteRecord', array(
//                        'package'=>$package, 'className'=>$className, 'recordId'=>$entity->getId() 
//                    ));
                    $row[] = "<a href='$editLink'><span class='glyphicon glyphicon-edit'></span></a>"
//                            ."<a href='$deleteLink'><span class='glyphicon glyphicon-trash'></span></a> "
                            ."$value";
                } else {
                    $row[] = $value;
                }
            }
            $result[] = $row;
	}

        return $result;

    }
    /**
     * Responds to XHR requests of the data tables module.
     */
    public function genericDataTablesDataSourceAction(Request $request, $package, $className){

        $classNames = $this->relatedClassNames($package, $className);
        $modelClass = new $classNames["model"];

        $columns = $modelClass::getTableViewColumnNames();
        $query = $modelClass::getRowViewQueryObject();

	$totalRecords = $query->count();

        // Output
	$response = array(
            "sEcho" => intval($request->get('sEcho')),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $query->count(),
            "data" => array()
	);

        // retrieve entities
        $entities = $this->findPaginatedSortedFiltered($request, $package, $className);

        // format in data tables response format
        $response['data'] = $this->formatAsArray($entities, $columns, $package, $className);

	return new Response(json_encode( $response ));

//        $_GET['iDisplayStart'] // offset
//        $_GET['iDisplayLength'] // number of records to send back at most

	/*
	 * Ordering
	 */
//        bSortable_<id>        is columns #id sortable?
//        iSortingCols          the number of columns by which is sorted
//        iSortCol_<id>         the id-th column to order by
//        sSortDir_<id>         asc/desc sort order for column #id
//        
//	$sOrder = "";
//	if ( isset( $_GET['iSortCol_0'] ) )
//	{
//		$sOrder = "ORDER BY  ";
//		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
//		{
//			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
//			{
//				$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
//					($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
//			}
//		}
//	}

	/*
	 * Filtering
	 */
//	$sWhere = "";
//	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
//	{
//		$sWhere = "WHERE (";
//		for ( $i=0 ; $i<count($aColumns) ; $i++ )
//		{
//			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
//		}
//		$sWhere = substr_replace( $sWhere, "", -3 );
//		$sWhere .= ')';
//	}
//	
//	/* Individual column filtering */
//	for ( $i=0 ; $i<count($aColumns) ; $i++ )
//	{
//		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
//		{
//			if ( $sWhere == "" )
//			{
//				$sWhere = "WHERE ";
//			}
//			else
//			{
//				$sWhere .= " AND ";
//			}
//			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
//		}
//	}

    }
    /**
     * Renders a list of all entities of a certain type.
     * Takes into account how the list should be rendered according to the XML schema.
     * In the schema this can be specified using the table_row_view behavior.
     * @param string $package      domain/object model package (Data, Classification, Workflow, Master)
     * @param string $className    model class
     */
    public function genericViewAllAction($package, $className, $updatedObjectId = 0) {

        $classNames = $this->relatedClassNames($package, $className);

        // for retrieving the column names
        $modelClass = new $classNames["model"];

//        $query = $modelClass::getRowViewQueryObject();
//        $recourds = $query->find();  // the data is provided by the data tables ajax response route 
        $records = array();

        return $this->renderWithDomainData("DTAMetadataBundle:ORM:genericViewAll.html.twig", array(
                    'className' => $className,
                    'columns' => $modelClass::getTableViewColumnNames(),
                    'data' => $records,
                    'updatedObjectId' => $updatedObjectId,
                ));
    }

    /**
     * Retrieves or creates (if the record ID is zero) an object of a model class.
     * @param array $entity Associative array with the parameters that identify a record: package, className, recordId
     * @return type The created or fetched entity.
     */
    protected function fetchOrCreate($entity){

        // generic create procedure for the model entity
        $package = $entity['package'];
        $className = $entity['className'];
        $recordId = $entity['recordId'];
        $classNames = $this->relatedClassNames($package, $className);

        if($recordId == 0){
            // create new object from class name
            $obj = new $classNames['model'];
        } else {
            // fetch the object from the database
            $queryObject = $classNames['model']::getRowViewQueryObject();
            $obj = $queryObject->findOneById($recordId);
        }

        return $obj;

    }
    /**
     * Core logic for creating/editing entities. Database logic and form creation.
     * Can be reused in the domain controllers and customized by passing additional options.
     * Also handles the POST HTTP requests that have been set off by the form.
     * @param model     $obj                The object to fill with data or to edit
     * @param array     $additionalLogic    An array with closures that covers additional actions
     *                                      'preSaveLogic' => closure // applied before saving.
     * @param array     $formTypeOptions    Options to influence the mapping of the object to a form
     *
     * @return array    Contains two fields, transaction (either 'edit', 'create', 'complete' or 'recordNotFound') which indicates
     *                  what happened and depending on the transaction outcome
     *                  the created entity, its id or the form to create it.
     */
    protected function genericCreateOrEdit(
            Request $request,
            $obj,
            $formTypeOptions = array(),
            $additionalLogic = null) {

        $additionalLogic = ($additionalLogic==null? function($o){}:$additionalLogic);

        // TODO compare form_row (form_div_layout.html.twig) error reporting mechanisms to the overriden version of form_row (viewConfigurationForModels.html.twig)
        // and test whether they work on different inputs.

        if( is_null($obj) ){
            return array('transaction'=>'recordNotFound');
        }

        $recordId = $obj->getId();

        // reflection
        $package = explode(".", $obj->getPeer()->getTableMap()->getPackage());
        $className = $obj->getPeer()->getTableMap()->getPhpName();
        $classNames = $this->relatedClassNames(array_pop($package), $className);

        $form = $this->createForm(new $classNames['formType'], $obj, $formTypeOptions);
        $form->handleRequest($request);

        $errors = array();

        // symfony validation: required fields etc.
        // also fails if the request doesn't contain POST data (form is displayed first, no submitted data)
        if ($form->isValid()) {

            $additionalLogic($obj);
            // propel validation: unique constraints etc.
            if ($obj->validate()) {

                $obj->save();

                // return edited/created entity ID as transaction success receipt
                return array(
                    'transaction'=>'complete',
                    'recordId'=>$form->getData()->getId(),
                    'object' => $obj,
                );

            } else { // propel validation fails

                $errors[] = "Propel validation failed.";
                // add propel validation messages to flash bag
                foreach ($obj->getValidationFailures() as $failure) {
                    $errors[] = $failure->getMessage();
                    $this->addErrorFlash($failure->getMessage());
                }
            }
        } else { // symfony form validation fails
            $errors = array();
            $this->collectErrorsRecursive($form, $errors);
            $combinedErrors = "";
            foreach ($errors as $error) {
                $combinedErrors .= $error->getMessage() . '<br/>';
            }
            if("" !== $combinedErrors){
                $this->addErrorFlash($combinedErrors);
            }
        }

        return array(
            'transaction'   => $recordId == 0 ? 'create' : 'edit',
            'form'          => $form,
            //DEBUG
            'request'       => $request
        );

    }

    private function collectErrorsRecursive($form, &$errors){
        // add symfony validation messages to flash bag
        foreach ($form->getErrors() as $error) {
            $errors[] = $error;
        }
        foreach($form->getIterator() as $child ){
            $this->collectErrorsRecursive($child, $errors);
        }
    }

    /**
     * Handles requests to generic create/edit request to any model class.
     * If specialized handlers exist in the domain controllers (that match the same route pattern)
     * these will be preferred by the router.
     * @param String    $package            The namespace/package name (Data/Workflow/Classification/Master)
     * @param String    $className          The model class name see src/DTA/MetadataBundle/Model/<$package>/ for classes
     * @param int       $recordId           The id of the entity to edit, 0 if new
     */
    public function genericCreateOrEditAction(Request $request, $package, $className, $recordId) {

        $obj = $this->fetchOrCreate( array(
            'package'   => $package,
            'className' => $className,
            'recordId'  => $recordId)
        );

        $result = $this->genericCreateOrEdit($request, $obj);

        return $this->handleResult($result, 'createOrEdit', $package, $className, $recordId);
    }

    protected function handleResult($result, $mode, $package, $className, $recordId){
        //$this->get('logger')->log('warning','HERE mode='.$mode);
        switch( $mode ){
            case 'createOrEdit':
                switch( $result['transaction'] ){
                    case "recordNotFound":
                        $this->addErrorFlash("Der gewünschte Datensatz kann nicht bearbeitet werden, weil er nicht gefunden wurde.");
                        $this->get('logger')->log('error', "Record not found: $package $className $recordId");
                        $target = $this->generateUrl($package.'_genericViewAll',array('package'=>$package, 'className'=>$className));
                        return $this->redirect($target);
                    case "complete":
                        $this->addSuccessFlash("Änderungen vorgenommen.");
                        $target = $this->generateUrl($package.'_genericViewAll',array('package'=>$package, 'className'=>$className));
                        return $this->redirect($target);
                    case "edit":
                    case "create":
                        return $this->renderWithDomainData("DTAMetadataBundle:ORM:createOrEdit.html.twig", array(
                                'form' => $result['form']->createView(),
                                'transaction' => $result['transaction'],    // whether the form is for edit or create
                                'className' => $className,
                                'recordId' => $recordId,
                                //DEBUG
                                'testData' => $result
                            ));
                }
            default:
                throw new Exception('Unknown mode: '.$mode);
        }
    }

    /**
     * Renders the form for the model without any surrounding elements.
     * Used via AJAX to create database entities.
     *
     * @param string $package       see above
     * @param string $className     name of the model class (e.g. Publication, Title, Titlefragment)
     * @param string $modalId       html id tag content for the modal to generate (for access in JS)
     * @param string $property      class member to access for getting a caption for the generated select option
     * @param int    $recordId       id of the record to edit. Zero indicates that a new record shall be created (since one is the smallest id)
     *
     */
    public function ajaxModalFormAction(Request $request, $package, $className, $modalId, $property = "Id", $recordId = 0) {

        $obj = $this->fetchOrCreate( array(
            'package'   => $package,
            'className' => $className,
            'recordId'  => $recordId)
        );

        $result = $this->genericCreateOrEdit(
                $request,
                $obj
        );

        switch( $result['transaction'] ){
            case "complete":
                // return an option for the select box to select the new entity
                $obj = $result['object'];
                $id = $obj->getId();
                $captionAccessor = "get" . $property;

                // check whether the caption accessor function exists
                $classNames = $this->relatedClassNames($package, $className);
                $cr = new \ReflectionClass($classNames['model']);
                if( ! $cr->hasMethod($captionAccessor) ){
                    throw new \Exception("Can't retrieve caption via $captionAccessor from $className object.");
                }

                $caption = $obj->$captionAccessor();
                return new Response("<option value='$id'>$caption</option>");

            case "edit":
            case "create":
                // plain ajax response, html form wrapped in twitter bootstrap modal markup
                return $this->renderWithDomainData("DTAMetadataBundle:ORM:ajaxModalForm.html.twig", array(
                            'form' => $result['form']->createView(),
                            'submitRouteParameters' => array(      // for generating the submit url
                                'package' => $package,
                                'recordId' => $recordId,
                                'className' => $className,
                                'modalId' => $modalId,
                                'property' => $property,
                            ),
                            'modalId' => $modalId,
                        )
                );
        }

    }

    /**
     * Returns the fully qualified class names for generic loading.
     * @param String $package   The namespace/package name of the class (Data/Workflow/Classification/Master)
     * @param String $className The basic name of the class, all lower-case except the first letter (Work, Personalname, Namefragmenttype)
     */
    public static function relatedClassNames($package, $className) {
        return array(
            "model"     => "DTA\\MetadataBundle\\Model\\$package\\" . $className,             // the actual propel active record
            "query"     => "DTA\\MetadataBundle\\Model\\$package\\" . $className . "Query",   // utility class for generating queries
            "peer"      => "DTA\\MetadataBundle\\Model\\$package\\" . $className . "Peer",    // utility class for reflection
            "formType"  => "DTA\\MetadataBundle\\Form\\$package\\" . $className . "Type",     // class for generating form inputs
            //"tableMap"       => "DTA\\MetadataBundle\\Model\\$package\\map\\" . $className . "TableMap",
        );
    }

    public static function getPackageName($fullyQualifiedClassName){
        $nameSpaceParts = explode('\\', $fullyQualifiedClassName);
        return $nameSpaceParts[count($nameSpaceParts)-2];
    }


    /**
     * Visits recursively all nested form elements and saves them.
     * Deprecated: this is propel's job. implement your own isChanged() method if propel doesn't save things recursively.
     * @param Form $form The form object that contains the data defined by the top level form type (PersonType, NamefragmentType, ...)
     */
//    protected function validateRecursively(\Symfony\Component\Form\Form $form) {
//
//        $entity = $form->getData();
//        if (is_object($entity)) {
//            $rc = new \ReflectionClass($entity);
//            if($rc->getName() === "PropelObjectCollection"){
//                foreach($entity as $e){
//                    $e->validate();
//                }
//            } elseif ($rc->hasMethod('validate')){
//                $validator->validate($entity->save());
//            }
//        }
//
//        foreach ($form->all() as $child) {
//            $this->saveRecursively($child);
//        }
//    }

    /**
     * Visits recursively all nested form elements and saves them.
     * Deprecated: this is propel's job. implement your own isChanged() method if propel doesn't save things recursively.
     * @param Form $form The form object that contains the data defined by the top level form type (PersonType, NamefragmentType, ...)
     */
//    protected function saveRecursively(\Symfony\Component\Form\Form $form) {
//
//        $entity = $form->getData();
//        if (is_object($entity)) {
//            $rc = new \ReflectionClass($entity);
//            if($rc->getName() === "PropelObjectCollection"){
//                foreach($entity as $e){
//                    $e->save();                    
//                }
//            } elseif ($rc->hasMethod('save')){
//                $entity->save();
//            }
//        }
//
//        foreach ($form->all() as $child) {
//            $this->saveRecursively($child);
//        }
//    }

}

    /*
     * @param string captionProperty For ajax use:
     *      Which attribute does the select use to describe the entities it lists?
     *      Used to generate a new select option via ajax.
     * @return HTML Option Element|nothing
     *      If the new action is called by a nested ajax form (selectOrAdd form type) the result is the option element to add to the nearby select.
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