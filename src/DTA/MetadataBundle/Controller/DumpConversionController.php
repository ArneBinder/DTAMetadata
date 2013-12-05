<?php
    
namespace DTA\MetadataBundle\Controller;
    
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DTA\MetadataBundle\Model;
    
/**
 * @author Carl Witt <carl.witt@fu-berlin.de>
 * Convert database dump of the old DTA project database (metadatenbank) by alex siebert
 * into new database schema by carl witt.
 */
class DumpConversionController extends ORMController {
    
    /**
     * Configure
     */
    private $username  = 'root';
    private $password  = 'root'; //garamond4000
    private $database  = "dtadb";
    private $dumpPath  = '/Users/macbookdata/Dropbox/DTA/dumpConversion/dtadb_2013-09-29_07-10-01.sql';
    private $mysqlExec = '/Applications/MAMP/Library/bin/mysql'; // for importing the dump
    private $phpExec   = '/usr/local/php5/bin/php';
        
    /** Stores problematic actions taken in the conversion process. */
    private $messages;
    private $warnings;
    private $errors;
    
    /**
     * @param type $username MySQL access parameters.
     * @param type $password MySQL access parameters.
     * @param type $database The schema name within the database.
     * @return \PDO
     * @throws Exception
     */
    function connect() {
        $dsn = "mysql:dbname=" . $this->database . ";host=127.0.0.1";
        try {
            return new \PDO($dsn, $this->username, $this->password);
        } catch (\PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }
        
    function dropAndSetupTargetDB(){
        
        // import dump
        $importDumpCommand = "$this->mysqlExec -u $this->username -p$this->password dtadb < $this->dumpPath";
        $this->messages[] = array("import dump command: " => $importDumpCommand);
        system($importDumpCommand);
        
        // build current database schema
        $result = system("$this->phpExec ../app/console propel:sql:build");
        $this->messages[] = array("building database schema from xml model: " => $result );
        
        // import current database schema to target database (ERASES ALL DATA)
        $result = system("$this->phpExec ../app/console propel:sql:insert --force");
        $this->messages[] = array("resetting target database: " => $result);
        
        // loads fixtures (task types, name fragment types, etc.)
        $result = system("$this->phpExec ../app/console propel:fixtures:load @DTAMetadataBundle");
        $this->messages[] = array("loading database fixtures: " => $result);
        
    }
    function convertAction() {
        
        // during conversion, a lot of memory is allocated
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        //
        // stores warning messages generated during the conversion
        $this->warnings = array();
        $this->messages = array();
        $this->errors   = array();
        
        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // ERASE ALL DATA FROM THE WORKING (TARGET DATABASE) vvvvvv
        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        
        $this->dropAndSetupTargetDB();
        
        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // ERASE ALL DATA FROM THE WORKING (TARGET DATABASE) ^^^^^^^
        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // 
        // connect to imported database
        $dbh = $this->connect();
            
        // remove some old records
        $this->cleanUpOldDatabase($dbh);
            
        // convert single entities
        
        $this->convertUsers($dbh);          // before publication because of last changed by user id
        
        $this->convertPublications($dbh);
        
        $this->convertPartners($dbh);
        
        $this->convertCopyLocations($dbh);  // after publications and partners
//        
        $this->convertTasks($dbh);          // after copy locations
//        
        $this->convertPublishingCompanies($dbh);
//        
        $this->convertPlaces($dbh);
//        
        $this->convertAuthors($dbh);        // after publication because during merging duplicate persons, information is easiest retained by adding the merged person as author via the known publication id
//        
        $this->convertSingleFieldPersons($dbh);
        
        return $this->renderWithDomainData('DTAMetadataBundle:DumpConversion:conversionResult.html.twig', array(
            'warnings' => $this->warnings,
            'messages' => $this->messages,
            'errors'   => $this->errors,
        ));
    }
        
    // parses date string in format 2007-12-11 17:39:30 to \DateTime objects
    function parseSQLDate($dateString){
        
        if($dateString === NULL)
            return NULL;
        
        $dateTime = date_parse($dateString);
        $result = new \DateTime();
        $result->setDate($dateTime['year'], $dateTime['month'], $dateTime['day']);
        $result->setTime($dateTime['hour'], $dateTime['minute'], $dateTime['second']);
        return $result;
    }
    
    /* ---------------------------------------------------------------------
     * partner
     * ------------------------------------------------------------------ */
    function convertPublications($dbh) {
        
        $rawData = "
            SELECT 
                book.id_book as id

                ,NULLIF(doi, '') as `doi`
                ,NULLIF(umfang, '') as `numpages`
                ,NULLIF(umfang_normiert, 0) as `numpages_numeric`
                ,NULLIF(title, '') as `title`
                ,NULLIF(subtitle, '') as `subtitle`
                ,NULLIF(other_title, '') as `subtitle2`
                ,NULLIF(short_title, '') as `shorttitle`
                ,NULLIF(dta_auflage, '') as `printrun`
                ,FIND_IN_SET(source,'china,don,kt,n/a') as `source_id`

                ,IF(LENGTH(`year`) < 3, NULL, `year`) as `year` -- to sort out a 0 entry
                ,LOCATE('[', `year`) as `year_is_reconstructed`

                ,CASE format
                    WHEN '' THEN NULL 
                    WHEN '4º' THEN '4°' 
                    WHEN '8º' THEN '8°'		-- merge character based differences
                    ELSE format
                END as `format`
    
                ,dta_comments
                ,special_comment as encoding_comment
                
                ,book.log_last_change
                ,user.id_user as `updated_by`

                ,NULLIF(dta_edition, '') as `edition`
                ,availability                                   -- is 0 only for 16 publications
                ,dta_insert_date                                -- is set for for approx. 40 publications
            FROM book 
                LEFT JOIN metadaten ON book.id_book = metadaten.id_book 
                LEFT JOIN sources   ON book.id_book = sources.id_book
                LEFT JOIN user      ON book.log_last_user = user.id_user
            ;";
        
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
    
            // title ------------------------------------------------------------------------------------
            $title = new Model\Data\Title();
            
            // iterate over title columns and create titlefragments of the according type
            $titleColumns = array("title"=>"Haupttitel", "subtitle"=>"Untertitel", "subtitle2"=>"Untertitel", "shorttitle"=>"Kurztitel", "printrun"=>"Auflage");
            $titleFragmentIdx = 1;
            foreach($titleColumns as $column=>$typeName){
                if($row[$column] !== NULL){
                    $fragment = new Model\Data\Titlefragment($typeName, $row[$column]);
                    $fragment->setSortableRank($titleFragmentIdx);
                    $title->addTitlefragment($fragment);
                    $titleFragmentIdx++;
                }
            }
            if($titleFragmentIdx == 1) 
                var_dump($row);
            
            // date ------------------------------------------------------------------------------------
            $publishedDate = NULL;
            if($row['year'] != NULL){
                $publishedDate = new Model\Data\Datespecification();
                $reconstructed = false;
                $year = $row["year"];
                if($row['year_is_reconstructed'] == "1"){
                    $reconstructed = true;
                    $year = str_replace("[", "", $year);
                    $year = str_replace("]", "", $year);
                }
                
                $publishedDate->setYear(intval($year, 10))
                              ->setYearIsReconstructed($reconstructed);
            }
            
            // infrequent data ------------------------------------------------------------------------------------
            // put some less frequent data (present only for few publications into the comment field
            $comment  = $row['dta_comments'];
            $comment .= $row['edition'] !== NULL ? "\nEdition: " . $row['edition'] : "";
            $comment .= $row['dta_insert_date'] !== NULL ? "\ndta_insert_date: " . $row['dta_insert_date'] : "";
            $comment .= $row['availability'] == "0" ? "\nGilt als nicht verfügbar." : "";

            // save ------------------------------------------------------------------------------------
            $publication = new Model\Data\Publication();
                
            $publication->setId($row['id'])
                        ->setTitle($title)
                        ->setDatespecificationRelatedByPublicationdateId($publishedDate)
                        ->setNumpages($row['numpages'])
                        ->setNumpagesnumeric($row['numpages_numeric'])
                        ->setComment($comment)
                        ->setEncodingComment($row['encoding_comment'])
                        ->setDoi($row['doi'])
                        ->setFormat($row['format'])
                        ->setSourceId($row['source_id'])
                        ->setUpdatedAt($this->parseSQLDate($row['log_last_change']))
                        ->setLastChangedByUserId($row['updated_by'])
                        ->save();
                
                

        }
    }
        
        
    /* ---------------------------------------------------------------------
     * partner
     * ------------------------------------------------------------------ */
    function convertPartners($dbh) {
        
        $rawData = "SELECT * FROM partner";
            
        $partner;
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
                        
            $partner = new Model\Workflow\Partner();
            $partner->setId($row['id_book_locations'])
                    ->setName($row['name'])
                    ->setContactPerson($row['person'])
                    ->setMail($row['mail'])
                    ->setWeb($row['web'])
                    ->setContactdata('Telefon: ' . $row['phone1'] . "\nAdresse: " . $row['adress'])
                    ->setComments($row['comments']);
            $partner->save();
        }
    }
        
      // after publications and partners
    function convertCopyLocations($dbh){
        
        $rawData = "SELECT 
                        id_Fundstellen as `copylocation_id`
                        ,book.id_book as `publication_id`
                        ,partner.id_book_locations as `partner_id`
                        ,NULLIF(fundstellen.dta_insert_date, '0000-00-00 00:00:00') as `created_at`
                        ,NULLIF(fundstellen.comments, '') as `comments`
                        ,NULLIF(`accessible`, 2) as `accessible`      -- 2 is currently used for 'not clear'
                        ,fundstellen.log_last_user as `updated_by`
                        ,fundstellen.log_last_change as `updated_at`
                        ,NULLIF(signatur, '') as `catalogue_signature`
                        ,NULLIF(bib_id, '') as `catalogue_internal`
                    FROM
                        fundstellen 
                        LEFT JOIN partner ON 
                            fundstellen.id_book_locations = partner.id_book_locations
                        LEFT JOIN book ON
                            fundstellen.id_book = book.id_book;";
            
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
                        
            if($row['publication_id'] === NULL){
                $this->errors[] = array('message' => 'copy location refers to non-existing publication.');
                continue;
            }
                
            try {

            $copyLocation = new Model\Workflow\CopyLocation();
            $copyLocation->setId($row['copylocation_id'])
                    ->setPublicationId($row['publication_id'])
                    ->setPartnerId($row['partner_id'])
                    ->setCreatedAt($row['created_at'])
                    ->setComments($row['comments'])
                    ->setAvailability($row['accessible'])
                    ->setUpdatedAt($row['updated_at'])
                    ->setCatalogueSignature($row['catalogue_signature'])
                    ->setCatalogueInternal($row['catalogue_internal']);
            $copyLocation->save();
            
            } catch (\PropelException $exc) {
                $this->errors[] = array('message' => 'on inserting copy location');
            }
        }
    }
            
    function convertTasks($dbh){
        
        $rawData = "SELECT 
                        id_task as `task_id`
                        ,IF(FIND_IN_SET(task_type,'5,10,20,30,31,40,45,50,55,58,59,60,65,70,75') = 0, null, task_type) as `task_type_id`
                        ,book.id_book as `publication_id`
                        ,partner.id_book_locations as `partner_id`
                        ,fundstellen.id_Fundstellen as `copy_location_id`
                        ,user.id_user as `user_id`
                        ,NULLIF(starttime, '0000-00-00 00:00:00') as `start_date`
                        ,NULLIF(endtime, '0000-00-00 00:00:00') as `end_date`
                        ,open_tasks.comments
                        ,closed
                        ,NULLIF(createDate, '0000-00-00 00:00:00') as `created_at`
                        ,NULLIF(open_tasks.log_last_change, '0000-00-00 00:00:00') as `updated_at`
                    FROM
                        open_tasks 
                        LEFT JOIN book ON
                                open_tasks.id_book = book.id_book
                        LEFT JOIN user on
                               open_tasks.id_user = user.id_user
                        LEFT JOIN partner ON 
                            open_tasks.id_book_locations = partner.id_book_locations
                        LEFT JOIN fundstellen ON
                            id_fundstelle = id_Fundstellen";
            
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });

            if($row['publication_id'] === NULL){
                $this->errors[] = array(
                    'message' => 'Task verweist auf nicht-existente Publikation.',
                    'action'  => 'Datensatz übersprungen',
                    'task_id'     => $row['task_id']
                    );
                continue;
            }
            
            if($row['task_type_id'] === NULL){
                $this->warnings[] = array(
                    'message' => "Task hat unbekannten Tasktyp.",
                    'action'  => 'Datensatz übersprungen',
                    'task_id'     => $row['task_id']
                    );
                continue;
            }
            
            try {

            $task = new Model\Workflow\Task();
            
            $task->setId($row['task_id'])
                    ->setTasktypeId($row['task_type_id'])
                    ->setPublicationId($row['publication_id'])
                    ->setPartnerId($row['partner_id'])
                    ->setCopylocationId($row['copy_location_id'])
                    ->setResponsibleuserId($row['user_id'])
                    ->setStartDate($this->parseSQLDate($row['start_date']))
                    ->setEndDate($this->parseSQLDate($row['end_date']))
                    ->setComments($row['comments'])
                    ->setClosed($row['closed'])
                    ->setCreatedAt($this->parseSQLDate($row['created_at']))
                    ->setUpdatedAt($this->parseSQLDate($row['updated_at']));
            $task->save();
            
            } catch (\PropelException $exc) {
                $this->errors[] = array('error' => 'on insert task', 'row' => $row);
            }
        }
        
    }
        
    /* ---------------------------------------------------------------------
     * publishing company
     * ------------------------------------------------------------------ */
         
    function convertPublishingCompanies($dbh) {
        
        $rawData = "SELECT publishing_company FROM
                    (SELECT dta_pub_verlag AS publishing_company FROM book 
                    UNION 
                    SELECT first_pub_verlag AS publishing_company FROM book) as pcs
                    where publishing_company <> ''
                    order by publishing_company";
                        
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
                        
            $publishingCompany = new Model\Data\Publishingcompany();
            $publishingCompany->setName($row['publishing_company'])
                    ->save();
        }
    }
    
    /* ---------------------------------------------------------------------
     * place
     * ------------------------------------------------------------------ */
         
    function convertPlaces($dbh) {
        
        $rawData = "
                SELECT DISTINCT
                    CASE TRIM(location) 
                        WHEN 'Frankfurt a. M' THEN 'Frankfurt (Main)'
                        WHEN 'Freiburg i. Br.' THEN 'Freiburg (Breisgau)'
                        WHEN 'Halle a. S.' THEN 'Halle (Saale)'
                        WHEN 'Leipzig (fingierte Druckorte)' THEN 'Leipzig'
                        ELSE TRIM(location)
                    END as `location`
                FROM
                    (SELECT DISTINCT 
                        SUBSTRING_INDEX(SUBSTRING_INDEX(location, ';', 1), ';', - 1) as location
                    FROM
                        (SELECT dta_pub_location AS location FROM book 
                         UNION SELECT first_pub_location AS location FROM book) as places
                        
                    UNION SELECT DISTINCT
                        SUBSTRING_INDEX(SUBSTRING_INDEX(location, ';', 2), ';', - 1) as location
                    FROM
                        (SELECT dta_pub_location AS location FROM book 
                         UNION SELECT first_pub_location AS location FROM book) as places
                        
                    UNION SELECT DISTINCT 
                        SUBSTRING_INDEX(SUBSTRING_INDEX(location, ';', 3), ';', - 1) as location
                    FROM
                        (SELECT dta_pub_location AS location FROM book 
                         UNION SELECT first_pub_location AS location FROM book) as places
                    ) as places
                WHERE
                    location IS NOT NULL
                    AND LENGTH(location) > 0
                ORDER BY 
                    TRIM(location)";
                        
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
                        
            $place = new Model\Data\Place();
            $place->setName($row['location'])
                    ->save();
        }
    }
    
    /* ---------------------------------------------------------------------
     * user
     * ------------------------------------------------------------------ */
         
    function convertUsers($dbh) {
        
        $rawData = "SELECT * FROM user";
            
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
                        
                        
            $user = new Model\Master\DtaUser();
                
            // password encryption
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $user->setSalt(md5(rand(-1239432, 23429304)));
            $saltedPassword = $encoder->encodePassword('$dta010', $user->getSalt());
                
            $user->setId($row['id_user'])
                    ->setUsername($row['name'])
                    ->setMail($row['mail'])
                    ->setPassword($saltedPassword);
                        
            $user->save();
        }
    }
        
    /* ---------------------------------------------------------------------
     * author
     * ------------------------------------------------------------------ */
         
    function convertAuthors($dbh) {
        
        // merge autor1_xxx and autor2_xxx columns into one table
        // autor3_xxx is never used.
        // TODO: two rows have a autor1_spelling entry ("Lutz" and Friedrich "<II., Preußen, König>")
        $rawData = "SELECT  
                      id_book
                      ,NULLIF(firstname, '') as firstname       -- NULLIF: replace empty strings with NULL
                      ,NULLIF(lastname, '') as lastname
                      ,NULLIF(pnd, '') as pnd
                    FROM (
                        SELECT 
                            id_book
                            ,TRIM(autor1_prename) as firstname 
                            ,TRIM(autor1_lastname) as lastname
                            ,TRIM(autor1_pnd) as pnd 
                        FROM book
                        WHERE autor1_prename <> '' OR autor1_lastname <> '' OR autor1_pnd <> ''
                        UNION 
                        SELECT 
                            id_book
                            ,TRIM(autor2_prename) as firstname
                            ,TRIM(autor2_lastname) as lastname 
                            ,TRIM(autor2_pnd) as pnd 
                        FROM book
                        WHERE autor2_prename <> '' OR autor2_lastname <> '' OR autor2_pnd <> ''
                    ) as names 
                    -- GROUP BY
                    --    lastname, firstname, pnd
                    ORDER BY
                        lastname, 
                        firstname, 
                        pnd DESC -- NULL pnds come second and the record with a pnd is used as base for the merge of subsequent persons with same name";
                            
        $lastPerson = NULL;
        $lastFirstname = NULL;
        $lastLastname = NULL;
        
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });

            $publication = Model\Data\PublicationQuery::create()
                        ->findOneById($row['id_book']);
            $gndCollisions = NULL;
            
            // try to detect duplicates with duplicate gnds
            if ($row['pnd'] !== NULL) {
                $gndCollisions = Model\Data\PersonQuery::create()
                        ->filterByGnd($row['pnd'])
                        ->find();
            }
            
            // subsequent rows usually contain the same person but a different book
            // if the row refers to the same person
            if($lastPerson !== NULL 
                    && $row['firstname'] == $lastFirstname 
                    && $row['lastname']  == $lastLastname){
                
                $publication
                    ->addPersonPublication(new Model\Master\PersonPublication($lastPerson->getId(), 'Autor'))
                    ->save();
                
            } else if($gndCollisions !== NULL && $gndCollisions->count() == 1){
                
                $publication
                    ->addPersonPublication(new Model\Master\PersonPublication($gndCollisions[0]->getId(), 'Autor'))
                    ->save();
                
            } else {
                
                // create the name object
                $name = new Model\Data\Personalname();
                if ($row['firstname'] !== NULL)
                    $name->addNamefragment(new Model\Data\Namefragment('Vorname', $row['firstname']));
                if ($row['lastname'] !== NULL)
                    $name->addNamefragment(new Model\Data\Namefragment('Nachname', $row['lastname']));
                
                $person = new Model\Data\Person();
                $person->setGnd($row['pnd'])            // does nothing if pnd is NULL
                        ->addPersonalname($name)
                        ->save();
                
                $lastPerson = $person;
                $lastFirstname = $row['firstname'];
                $lastLastname  = $row['lastname'];
                
                $publication
                    ->addPersonPublication(new Model\Master\PersonPublication($person->getId(), 'Autor'))
                    ->save();
            }
                
        }
    }
        
    /* ---------------------------------------------------------------------
     * translator, publisher, author (with the entire information stored in a single string)
     * some columns also contain more than one person, separated by semicolons
     * ------------------------------------------------------------------ */
         
    function convertSingleFieldPersons($dbh) {
        
        // there are a few persons which don't have first name/last name columns, so the names must be split
        $rawData = "
            SELECT 
                -- id_book,
                TRIM(person) as person,
                LOCATE('#', person) as hash_position
                ,LOCATE(',', person) as comma_position
                ,LOCATE(' ', person) as space_position
            FROM (
                
            -- first persons (if separated by ';')
                SELECT DISTINCT 
                        id_book, SUBSTRING_INDEX( SUBSTRING_INDEX( person, ';', 1), ';', -1 ) as person
                FROM (
                    SELECT id_book, uebersetzer AS person FROM book
                    UNION
                    SELECT id_book, publisher AS person FROM book
                    UNION
                    SELECT id_book, dta_in_autor AS person FROM book
                    UNION
                    SELECT id_book, autor1_syn_names AS person FROM book
                ) as condensedNames 
                WHERE person IS NOT NULL AND LENGTH(person) > 2 	-- for some reason, strings of length 2 survive the trim operation
                    
                UNION
                    
            -- second persons (if separated by ';')
                SELECT DISTINCT 
                        id_book, SUBSTRING_INDEX( SUBSTRING_INDEX( person, ';', 2), ';', -1 ) as person
                FROM (
                    SELECT id_book, uebersetzer AS person FROM book
                    UNION
                    SELECT id_book, publisher AS person FROM book
                    UNION
                    SELECT id_book, dta_in_autor AS person FROM book
                    UNION
                    SELECT id_book, autor1_syn_names AS person FROM book
                ) as condensedNames 
                WHERE person IS NOT NULL AND LENGTH(person) > 2
            ) as names
            ORDER BY person";
                
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
                        
            $person = new Model\Data\Person();
            $gnd = NULL;
            $name = new Model\Data\Personalname();
            
            // if there's a GND finding duplicates is mostly simple
            // also remove the GND string part from the name to make splitting easier
            if ($row['hash_position'] !== 0) {
                
                $gnd = substr($row['person'], $row['hash_position']);
                // remove the gnd
                $row['person'] = substr($row['person'], 0, $row['hash_position'] - 2);
                    
                $collision = Model\Data\PersonQuery::create()->findOneByGnd($gnd);
                if ($collision !== NULL){
                    
                    $this->warnings[] = array(
                        'message' => 'Personen-Duplikat: GND bereits vergeben.',
                        'action' => 'Datensatz übersprungen.',
                        'record' => $row,
                        'collision due to' => "ID: " . $collision->getId() . "; " . $collision->getRepresentativePersonalName());
                }
                continue;
            } // if no GND is given, no duplicate detection is performed.
            // assume a ',' is indicating first name, last name format
            if ($row['comma_position'] !== 0) {
                $parts = explode(',', $row['person']);
                $name->addNamefragment(new Model\Data\Namefragment('Nachname', $parts[0]));
                $name->addNamefragment(new Model\Data\Namefragment('Vorname', $parts[1]));
            } elseif ($row['space_position'] !== 0) {
                $parts = explode(',', $row['person']);
                $name->addNamefragment(new Model\Data\Namefragment('Vorname', $parts[0]));
                $name->addNamefragment(new Model\Data\Namefragment('Nachname', $parts[1]));
            } else {
                $name->addNamefragment(new Model\Data\Namefragment('Nachname', $row['person']));
            }
                
            // create person
            $person->setGnd($gnd)
                    ->addPersonalname($name)
                    ->save();
        }
    }
        
    function cleanUpOldDatabase($dbh) {
        
        // remove unused tables
        $queries[] = "DROP table `dtadb`.`corpus_use`;
                      DROP table `dtadb`.`lastusergroups`;";
                          
        // remove unused attributes
        $queries[] = "ALTER TABLE `dtadb`.`book` DROP COLUMN `dta_quelle`, DROP INDEX `Index_5` ;";
            
        $queries[] = "ALTER TABLE `dtadb`.`open_tasks` DROP COLUMN `realendtime`;
                      ALTER TABLE `dtadb`.`open_tasks` DROP COLUMN `parent_task`;";
                          
        $queries[] = "ALTER TABLE `dtadb`.`open_tasks_groups` DROP COLUMN `realendtime`;
                      ALTER TABLE `dtadb`.`open_tasks_groups` DROP COLUMN `comments`;
                      ALTER TABLE `dtadb`.`open_tasks_groups` DROP COLUMN `parent_task`;";
                          
        $queries[] = "ALTER TABLE `dtadb`.`user` DROP COLUMN `creation_date`;
                      ALTER TABLE `dtadb`.`user` DROP COLUMN `last_use`;
                      ALTER TABLE `dtadb`.`user` DROP COLUMN `last_book_id`;";
                          
        // !!! remove an old test record
            
        $queries[] = "DELETE FROM `dtadb`.`book` WHERE `id_book`='17251';
                      DELETE FROM `dtadb`.`metadaten` WHERE `id_book`='17251';";
                          
        // !!!
            
        foreach ($queries as $query) {
            $this->messages[] = array("clean up database command: " => $query);
            $dbh->query($query);
        }
    }
        
    function nearDuplicateProposalAction() {
        
        $dbh = $this->connect();
            
        $personsQ = "
            SELECT DISTINCT * FROM
                (SELECT CONCAT( TRIM(autor1_prename), ' ', TRIM(autor1_lastname)) FROM book 
                UNION
                 SELECT CONCAT( TRIM(autor2_prename), ' ', TRIM(autor2_lastname)) FROM book )
                as pcs";
                    
        $array = $dbh->query($personsQ)->fetchAll(\PDO::FETCH_COLUMN, 0);
//        print_r($array);
        // search split 
            
        $candidates = array();
        for ($i = 0; $i < count($array); $i++) {
            for ($j = $i; $j < count($array); $j++) {
                $similarity = -1;
                similar_text($array[$i], $array[$j], $similarity);
                if ($similarity > 70 && $array[$i] != $array[$j]) {
                    echo $array[$i] . "<br/> " . $array[$j] . "<br/><br/>";
                    $candidates[] = array($array[$i], $array[$j]);
                }
            }
        }
            
            
//        foreach ($candidates as &$pair) {
//            echo $pair[0] . "<br/>";
//            echo $pair[1] . "<br/><br/>";
//            
//            $books = 
//            "Select 
//                title, `year`, dta_pub_verlag, autor1_prename, autor1_lastname
//            from
//                book
//            where
//                dta_pub_verlag in (\"$pair[0]\", \"$pair[1]\")
//            order by `dta_pub_verlag`";
//            
//            $pair['books'] = $dbh->query($books)->fetchAll();
//        }
//        
//        return $this->render("DTAMetadataBundle:DumpConversion:pcDuplicates.csv.twig", array('candidates' => $candidates));
    }
        
    function nearDuplicateProposalAction_PublicationCompany() {
        $array = array("Ackermann", "Agentur des Rauhen Hauses", "Akademie der Wissenschaften", "Akademische Buchhandlung", "Alberti", "Allgemeinen Sport-Zeitung", "Amelang", "Andreä", "Arnold", "Aschendorff", "Asher & Co.", "Barth", "Barthol", "Bassermann", "Bauer", "Baumgärtner", "Beck", "Bibliographisches Institut", "Bildungs-Gesellschaft für Arbeiter", "Blätter für die Kunst", "Bohn", "Bonz", "Bornträger", "Braumüller", "Braun", "Braun und Schneider", "Breitkopf & Härtel", "Klemm", "Breitkopf und Härtel", "Brockhaus", "Brockhaus & Avenarius, Schrag", "Brunet", "Buchhandlung der Erziehungsanstalt", "Buchhandlung des Waisenhauses", "Bädeker", "Böhlau", "Cassirer", "Christmann", "Cohen", "Conrad", "Coppenrath", "Cosmos", "Costenoble", "Cotta", "Cotta; Kröner", "Craz", "Creutz", "Curt", "Decker", "Deichert", "Diederichs", "Dienemann", "Dieterich", "Dieterichsche Buchhandlung", "Dietz", "Dorn", "Duncker", "Duncker & Humblot", "Duncker und Humblot", "Dyk", "Dümmler", "Elischer", "Engelhardt", "Engelhorn", "Engelmann", "Enke", "Entsch", "Ernst", "Ernst & Korn", "Ettinger", "Fehsenfeld", "Felix", "Fischer", "Flammer und Hoffmann", "Fleischer", "Fontane", "Fontane & Co.", "Franckh", "Fricke", "Friedrich", "Fritzsch", "Frommann", "Frölich", "Fues", "Gaertner", "Gerold", "Gerstenberg", "Giel", "Giesecke & Devrient", "Goebhardt", "Groos", "Grote", "Groß", "Grädener & Richter", "Gräff", "Gräffer", "Guilhauman", "Guttentag", "Göschen", "Haack", "Habel", "Haering", "Haessel", "Hahn", "Hallberger", "Hammerich", "Hartknoch", "Hartleben", "Haude und Spener", "Heckenast", "Heckenast; Wigand", "Heinrichshofen", "Helwing", "Henne", "Hermann", "Hertz", "Hesse", "Heyer", "Heyne", "Hilscher", "Himburg", "Hirschfeld", "Hirschwald", "Hirzel", "Hitzig", "Hoff", "Hoffmann", "Hoffmann und Campe", "Hofmann", "Hrsg. d. Deutschen Zeitung; Göschen", "Huth", "Hölder", "Hölscher", "in Commission in der J. C. Hermannschen Buchhandlung", "Industrie-Comptoir", "Insel", "Issleib", "Janke", "Jent & Gaßmann", "Junius", "Kayser", "Keil", "Klostermann", "Knapp", "Korn", "Krabbe", "Kreidel", "Kriele", "Kummer", "Künast", "Lagarde und Friederich", "Landgraf", "Langen", "Laupp", "Lentner", "Levysohn", "Lewy", "Liesching", "Literarische Anstalt", "Literarisches Comptoir", "Literarisches Institut", "Loewes", "Logier", "Lorck", "Lucas", "Lüderitz", "Lüstenöder", "Löwenthal", "Macklot", "Mallinckrodt", "Matzdorff", "Maurer", "Max", "Mayer", "Meidinger", "Meissner", "Meißner", "Mendelssohn", "Meyer", "Michaelis und Bispink", "Mittler", "Mohr", "Mohr u: Zimmer", "Mohr und Zimmer", "Mühlmann", "Mylius", "Mäcken", "Naturforschender Verein", "Nauck", "Naumann", "Neff", "Nicolai", "Nicolovius", "Niemeyer", "Normalschul-Buchdruckerey", "Oldenbourg", "Oppenheim", "Orell", "Orell, Gessner, Füssli", "Osiander", "Paetel", "Palm", "Parey", "Perrenon", "Perthes", "Perthes und Besser", "Pfeffer", "Rackhorst", "Realschulbuchhandlung", "Reimarus", "Reimer", "Rein", "Reissner", "Richter", "Riemann", "Rosner", "Rücker", "Rümpler; Grimpe", "Röwer", "Sander", "Sassenbach", "Sauerländer", "Schade", "Schaub", "Schickhardt & Ebner", "Schmeitzner", "Schmerber", "Schmidt", "Schmorl & von Seefeld", "Schott", "Schrag", "Schroedel & Simon", "Schroeder", "Schul-Buchandlung", "Schulbuchhandlung", "Schultze", "Schuster & Loeffler", "Schwan", "Schweighauser", "Schweizerbart", "Schwers", "Schwetschke", "Seemann", "Selbstverlag", "Seydel", "Siemens", "Sollinger", "Spemann", "Springer", "Spurny", "Stage", "Stahel", "Steffens", "Strauß", "Tauchnitz", "Tempsky", "Teubner", "Toeche; Pormetter", "Trewendt", "Trübner", "Unger", "Unzer", "Urania", "Vaillant-Carmanne", "Vandenhoeck", "Vandenhoeck und Ruprecht", "Vandenhoek und Ruprecht", "Veit", "Veit & Co.", "Velhagen & Klasing", "Velhagen und Klasing", "Vereins-Buchhandlung", "Vereinsbuchhandlung", "Verlag der Instituts-Buchh. und in Commission bei Crusius ", "Verlag der Volksbuchhandlung", "Verlag des Bibliographischen Bureaus", "Verlags-Magazin", "Vieweg", "Vogel", "Voigt", "Voss", "Voß", "Wagner", "Wallishauser", "Wallishauser", "Wallishausser", "Walther", "Weber", "Weidmann", "Weidmann Erben und Reich", "Weidmanns Erben und Reich", "Weigel und Schneider", "Weise", "Weiß", "Wiegand, Hempel & Parsey", "Wiegandt, Hempel & Parsey", "Wiener Verlag", "Wigand", "Wilmans", "Winter", "Metzler");
            
        $candidates = array();
        for ($i = 0; $i < count($array); $i++) {
            for ($j = $i + 1; $j < count($array); $j++) {
                $similarity = -1;
                similar_text($array[$i], $array[$j], $similarity);
                if ($similarity > 80 && $array[$i] != $array[$j]) {
//                    echo $array[$i] . "<br/> " . $array[$j] . "<br/><br/>";
                    $candidates[] = array($array[$i], $array[$j]);
                }
            }
        }
            
        // find books for candidates
        $dbh = $this->connect();
        foreach ($candidates as &$pair) {
            echo $pair[0] . "<br/>";
            echo $pair[1] . "<br/><br/>";
                
            $books =
                    "Select 
                title, `year`, dta_pub_verlag, autor1_prename, autor1_lastname
            from
                book
            where
                dta_pub_verlag in (\"$pair[0]\", \"$pair[1]\")
            order by `dta_pub_verlag`";
                
            $pair['books'] = $dbh->query($books)->fetchAll();
        }
            
        return $this->render("DTAMetadataBundle:DumpConversion:pcDuplicates.csv.twig", array('candidates' => $candidates));
    }
        
}
    
?>