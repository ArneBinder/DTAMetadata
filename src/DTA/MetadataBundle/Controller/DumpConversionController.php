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
    
    /** Connection used in the target database, can be used to wrap multiple queries in a single transaction for a small speedup. */
    private $propelConnection;
    
    /** For convenience, old Ids are retained whereever possible in the new database. 
     * This requires auto-increment to be off for some id columns and this counter can be used to get a free publication ID, since 
     * publication Ids in the old databse start somewhere from 16000 upwards.
     */
    private $publicationIdCounter = 1;
    
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
            throw new \Exception("Connection failed: " . $e->getMessage());
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
        ini_set('memory_limit', '1200M');
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

        // connect to imported (legacy) database
        $dbh = $this->connect();
            
        // trim and NULL empty strings, remove some old records
        $this->cleanUpOldDatabase($dbh);
        // assert that certain assumptions hold for the dump (unused fields)
        $this->checkOldDatabase($dbh);
        
        $this->propelConnection = \Propel::getConnection(Model\Master\DtaUserPeer::DATABASE_NAME);
        $this->messages[] = array('message' => 'transaction begun on '.Model\Master\DtaUserPeer::DATABASE_NAME);
        
        $this->createTaskTypes();
        
        $this->convertUsers($dbh);          // before publication because of last changed by user id
        
        $this->convertFonts($dbh);          // before publication
        
        
        $this->propelConnection->beginTransaction();
        $this->convertPublications($dbh);
        $this->propelConnection->commit();

        $this->convertFirstEditions($dbh);  // after publication because a first edition entity knows the publication id that links to it
        
        $this->convertPublicationGroups($dbh);
        
        $this->propelConnection->beginTransaction();
        $this->convertPartners($dbh);
        $this->propelConnection->commit();
        
        $this->propelConnection->beginTransaction();
        $this->convertCopyLocations($dbh);  // after publications and partners
        $this->propelConnection->commit();
        
        $this->propelConnection->beginTransaction();
        $this->convertTasks($dbh);          // after copy locations
        $this->propelConnection->commit();
        
        $this->propelConnection->beginTransaction();
        $this->convertPublishingCompanies($dbh);
        $this->propelConnection->commit();

        $this->propelConnection->beginTransaction();
        $this->convertPlaces($dbh);
        $this->propelConnection->commit();
        
        $this->convertAuthors($dbh);        // after publication because during merging duplicate persons, information is easiest retained by adding the merged person as author via the known publication id
        
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
    
    function createTaskTypes(){
        
        $taskTypes = array( 
            2 => array(
                'name'=>'Gruppe A: Double Keying',
                'children' => array(
                    array(10=>'Textbeschaffung'),
                    array(58=>'Vorkorrektur'),
                    array(30=>'Zoning'),
                    array(50=>'Abtippen'),
                    array(59=>'Convert2TEIP5'),
                 )
            ),
            3=>array(
                'name'=>'Gruppe B',
                'children' => array(
                    array(31=>'GrobiZoning'),
                    array(45=>'OCR2')
                )
            ),
            4=>array(
                'name' => 'Gruppe C',
                'children' => array(
                    array(20=>'Scannen (fakultativ)'))
            ),
            5=>array(
                'name'=>'Gruppe D (OCR-Workflow, nach Zoning)',
                'children' => array(
                    array(40=>'OCR'),
                    array(55=>'DON'),
                    array(60=>'Compare'),
                    array(65=>'DON2XML'),
                    array(75=>'CoordinateMerge_Don'),
                    array(70=>'Nachkorrektur'))
            )
        );
        
        $root = new Model\Workflow\Tasktype();
        $root->setId(1)
            ->setName('Workflows')
            ->makeRoot()
            ->save();
        
        foreach ($taskTypes as $id => $workflow) {
            $taskType = new Model\Workflow\Tasktype();
            $taskType->setId($id)
                    ->setName($workflow['name'])
                    ->insertAsLastChildOf($root)
                    ->save();
            
            foreach ($workflow['children'] as $child) {
                $childType = new Model\Workflow\Tasktype();
                $childType->setId(array_keys($child)[0])
                        ->setName($child[array_keys($child)[0]])
                        ->insertAsLastChildOf($taskType)
                        ->save();
                
            }
            
        }
        
    }
    
    function convertFonts($dbh){

        $rawData = "
            SELECT 
                schriftart as `font_name`
            FROM metadaten WHERE length(schriftart) > 0
            GROUP BY schriftart;";
        
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
            
            $font = new Model\Data\Font();
            $font->setName($row['font_name'])
                 ->save();
        }
    }
    
    /** Returns an array of multi-volumed publications according to the criteria of the old system:
     *  The title and first authors last name match.
     *  Returns an array structured like this: array(
     *  [title concatenated with autor1_lastname] =>
     * array(
     *      'title' => [title],
     *      'author_lastname' => [autor1_lastname],
     *      'volumes_present' => [count(*)],
     *      'multiVolume'     => null
     * )
     */
    function findMultiVolumes($dbh) {
        
        $rawData = "
            SELECT 
                title, autor1_lastname, count(*) as `count`
            FROM
                book join metadaten on book.id_book = metadaten.id_book
            WHERE 
                    metadaten.type in ('MM', 'MMS')
            GROUP BY title , autor1_lastname
            ;";
        
        $multiVolumes = array();
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
            
            $multiVolumes[$row['title'].$row['autor1_lastname']] = array(
                'title'=>$row['title'],
                'author_lastname'=>$row['autor1_lastname'],
                'volumes_present'=>$row['count'],
                'multiVolume'=>NULL,
            );
            
        }
        
        return $multiVolumes;
    }
    
    private function createMultiVolume($row, &$publication, $title, &$multiVolumes){
        
        // the parent publication (a multi-volume) to which the volume shall be added
        $multiVolume = $multiVolumes[$row['title'].$row['autor1_lastname']]['multiVolume'];

        // create the multi-volume if it doesn't already exist
        if($multiVolume === NULL){

            // create a new publication for the multi-volume
            $basePublication = new Model\Data\Publication();
            $basePublication->setId($this->publicationIdCounter++)
                            ->setTreeId($this->publicationIdCounter-1)
                            ->makeRoot()
                            ->setType(Model\Data\PublicationPeer::TYPE_MULTIVOLUME)
                            ->setTitle($title)
                            ->save();

            // create the multi-volume
            $multiVolume = new Model\Data\MultiVolume();
            $multiVolume->setId($basePublication->getId())
                        ->setVolumesTotal($row['volumes_total'])
                        ->save($this->propelConnection);

            // make the multi-volume accessible to upcoming volumes of this work
            $multiVolumes[$row['title'].$row['autor1_lastname']]['multiVolume'] = $multiVolume;

        }

        // check if the specified number of volumes matches the actual number of volumes in the database
        if($multiVolumes[$row['title'].$row['autor1_lastname']]['volumes_present'] != $row['volumes_total'])
            $this->warnings[] = array(
                'message'=>'Angabe der Gesamtzahl Bände weicht von den tatsächlich in der Datenbank vorhandenen Bänden ab.'
                ,'Titel'=>$row['title']
                ,'Autor'=>$row['autor1_lastname']
                ,'book.id_book'=>$row['id']
                ,'In der Datenbank vorhanden'=>$multiVolumes[$row['title'].$row['autor1_lastname']]['volumes_present']
                ,'Ausgegeben'=>$row['volumes_total']
        );

        // link the volume to its containing multi-volume

        $publication->setTreeId($this->publicationIdCounter-1)
//                        ->setParent($multiVolume->getPublication())
                    ->insertAsLastChildOf($multiVolume->getPublication())
                    ->setType(Model\Data\PublicationPeer::TYPE_VOLUME)
                    ->save($this->propelConnection);

        $volume = new Model\Data\Volume();
        $volume->setVolumeDescription($row['volume_description'])
               ->setVolumeNumeric($row['volume_numeric'])
               ->setPublication($publication)
               ->save($this->propelConnection);
        
        
    }
    
    /** Adds the information about first edition (Erstausgabe) to the publication.  
        Since mostly, only a year, location and a publishing company are needed, the entire information is stored in a plain text format. */
    function convertFirstEditions($dbh){
        
        $rawData = "SELECT 
                        book.id_book as id,
                        first_pub_date,
                        first_pub_name,
                        first_pub_location,
                        first_pub_verlag,
                        first_reihe_titel,
                        first_seiten,
                        first_reihe_band,
                        first_comments,
                        CASE first_status
                            WHEN '0' THEN NULL
                            WHEN '1' THEN _utf8'Erstveröffentlichung'
                            WHEN '2' THEN _utf8'Keine Erstveröffentlichung'
                            WHEN '3' THEN _utf8'Unklar, ob Erstveröffentlichung'
                            ELSE first_status
                        END as `first_status`
                    FROM
                        book
                    WHERE
                        first_pub_date is not null
                            OR first_pub_location is not null
                            OR first_pub_verlag is not null
                            OR first_reihe_titel is not null
                            OR first_seiten is not null
                            OR first_reihe_band is not null
                            OR first_comments is not null
                            OR (first_status is not null and first_status > 0)";
        
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });

            $fields = array(
                'first_status',
                'first_pub_date',
                'first_pub_name',
                'first_pub_verlag',
                'first_pub_location',
                'first_reihe_titel',
                'first_reihe_band',
                'first_seiten',
                'first_comments');
            $labels = array(
                'Status',
                'Erschienen', 
                'Herausgeber',
                'Verlag', 
                'Ort',
                'Titel (R/Z)',
                'Band (R/Z)',
                'Seitenangabe', 
                'Kommentar', 
            );
            
            $firstEditionData = "";
            for ($fieldIdx = 0; $fieldIdx < count($fields); $fieldIdx++) {
                if($row[$fields[$fieldIdx]] !== NULL ){
                    $firstEditionData .= $labels[$fieldIdx] . ": " . $row[$fields[$fieldIdx]] . "\n";
                }
            }
            
            $publication = Model\Data\PublicationQuery::create()
                    ->findOneById($row['id']);
            $publication->setFirsteditionComment($firstEditionData)
                        ->save();
            
            
        }
            
    }
    
    function convertPublications($dbh) {
        
        $rawData = "
            SELECT 
                book.id_book as id

                ,title as `title`
                ,subtitle as `subtitle`
                ,other_title as `subtitle2`
                ,short_title as `shorttitle`
                ,dta_auflage as `printrun`
                ,dta_bibl_angabe as `citation`
                ,FIND_IN_SET(sources.source,'china,don,kt,n/a') as `source_id`

                ,IF(LENGTH(`year`) < 3, NULL, `year`) as `year` -- to sort out a 0 entry
                ,LOCATE('[', `year`) as `year_is_reconstructed`

                ,CASE format
                    WHEN '' THEN NULL 
                    WHEN '4º' THEN '4°' 
                    WHEN '8º' THEN '8°'		-- merge character based differences
                    ELSE format
                END as `format`
    
                ,dta_comments as `dta_comments`
                ,special_comment as encoding_comment
                ,metadaten.planung as `metadata_comment`
                ,dta_comment2 as `edition_comment`
                
                ,dirname as `dirname`
                
                ,genre as `genre`
                ,untergenre as `subgenre`
                ,metadaten.dwds_kategorie1
                ,metadaten.dwds_unterkategorie1
                ,metadaten.dwds_kategorie2
                ,metadaten.dwds_unterkategorie2
                ,type as legacy_type
                ,CASE type
                    WHEN 'M'  THEN 'BOOK'
                    WHEN 'MS' THEN 'BOOK'
                    WHEN 'X'     THEN 'BOOK'
                    WHEN 'MM' THEN 'VOLUME'
                    WHEN 'MMS' THEN 'VOLUME'
                    WHEN 'DM' THEN 'CHAPTER'
                    WHEN 'DS' THEN 'CHAPTER'
                    WHEN 'JA' THEN 'ARTICLE'
                    WHEN 'Reihe' THEN 'SERIES'
                    WHEN 'Zeitschrift' THEN 'JOURNAL'
                    ELSE type
                END as `publication_type`
                
                ,NULLIF(band_zaehlung, 0) as `volume_numeric`
                ,NULLIF(band_anzahl, 0) as `volumes_total`
                ,band_alphanum as `volume_description`
                ,autor1_lastname    -- to detect multi-volume publications, the author name and title need to match
                
                ,doi as `doi`
                ,umfang as `numpages`
                ,umfang_normiert as `numpages_numeric`
                ,dta_seiten as `pages`
                ,book.log_last_change
                ,user.id_user as `updated_by`
                ,usecase as `usecase`

                ,dta_edition as `edition`
                ,availability                                   -- is 0 only for 16 publications
                ,book.dta_insert_date                           -- is set for for approx. 40 publications
                
                ,fundstellen.id_Fundstellen as `used_copy_location`
                ,NULLIF(startseite,0) as `first_text_page`
                
            FROM book 
                LEFT JOIN metadaten ON book.id_book = metadaten.id_book 
                LEFT JOIN sources   ON book.id_book = sources.id_book
                LEFT JOIN user      ON book.log_last_user = user.id_user
                LEFT JOIN fundstellen ON id_nachweis = id_Fundstellen
            ;";
        
        $multiVolumes = $this->findMultiVolumes($dbh);
        
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
    
            // title ------------------------------------------------------------------------------------
            $title = new Model\Data\Title();
            
            // iterate over title columns and create titlefragments of the according type
            $titleColumns = array("title"=>"Haupttitel", "subtitle"=>"Untertitel", "subtitle2"=>"Untertitel", "shorttitle"=>"Kurztitel");
            $titleFragmentIdx = 1;
            foreach($titleColumns as $column=>$typeName){
                if($row[$column] !== NULL){
                    $fragment = new Model\Data\Titlefragment($typeName, $row[$column]);
                    $fragment->setSortableRank($titleFragmentIdx);
                    $title->addTitlefragment($fragment);
                    $titleFragmentIdx++;
                }
            }
            if($titleFragmentIdx == 1) $this->errors[] = array('message'=>'Keine Titelangabe gefunden für','book.id_book'=>$row['id']);
            
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
            $comment .= $row['availability'] == "0" ? "\nGilt als nicht verfügbar." : "";
            $comment .= $row['usecase'] !== NULL ? "\nGrund der Korpuszugehörigkeit: " . $row['usecase'] : "";
            $comment .= $row['metadata_comment'] !== NULL ? "\nKommentar Planung/Metadaten: " . $row['metadata_comment'] : "";
            
            // save ------------------------------------------------------------------------------------
            
            // basic publication data 
            $publication = new Model\Data\Publication();
            $publication->setId($row['id'])
                        ->setDirname($row['dirname'])
                        ->setTitle($title)
                        ->setDatespecificationRelatedByPublicationdateId($publishedDate)
                        ->setNumpages($row['numpages'])
                        ->setNumpagesnumeric($row['numpages_numeric'])
                        ->setCitation($row['citation'])
                        ->setPrintrun($row['printrun'])
                        ->setComment($comment)
                        ->setFirstpage($row['first_text_page'])
                        ->setUsedcopylocationId($row['used_copy_location'])
                        ->setEditioncomment($row['edition_comment'])
                        ->setEncodingComment($row['encoding_comment'])
                        ->setDoi($row['doi'])
                        ->setFormat($row['format'])
                        ->setSourceId($row['source_id'])
                        ->setLegacygenre($row['genre'])
                        ->setLegacysubgenre($row['subgenre'])
                        ->setLegacyDwdsCategory1($row['dwds_kategorie1'])
                        ->setLegacyDwdsSubcategory1($row['dwds_unterkategorie1'])
                        ->setLegacyDwdsCategory2($row['dwds_kategorie2'])
                        ->setLegacyDwdsSubcategory2($row['dwds_unterkategorie2'])
                        ->setLegacytype($row['legacy_type'])
                        ->setCreatedAt($this->parseSQLDate($row['dta_insert_date']))
                        ->setUpdatedAt($this->parseSQLDate($row['log_last_change']))
                        ->setLastChangedByUserId($row['updated_by']);

            
            if(array_key_exists($row['title'].$row['autor1_lastname'], $multiVolumes)){
                
                $this->createMultiVolume($row, $publication, $title, $multiVolumes);
                
            } else {
                
                // for non volumes, just save a basic publication
                switch($row['publication_type']){
                    case "ARTICLE":
                        $article = new Model\Data\Article();
                        $article->setPublication($publication)
                                ->setPages($row['pages'])
                                ->save();
                        break;
                    case "CHAPTER":
                        $chapter = new Model\Data\Chapter();
                        $chapter->setPublication($publication)
                                ->setPages($row['pages'])
                                ->save();
                        break;
                }
                
                $publication->setType($row['publication_type'])
                            ->save($this->propelConnection);
                
            }
        
            
        }// end for book rows
    }
    
    function convertPublicationGroups($dbh){
        
        $rawData = "SELECT 
                        book.id_book as publication_id, 
                        groups.id_group as group_id, 
                        group_name,
                        groups.log_last_change as 'last_change'
                    FROM groups, group_books, book
                    WHERE
                            book.id_book = group_books.id_book
                            AND group_books.id_group = groups.id_group
                    ORDER BY groups.id_group";
            
        $lastGroupId = -1;
        $group = NULL;
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });

            if($row['group_id'] !== $lastGroupId){
                
                $group = new Model\Workflow\Publicationgroup();
                $group->setId($row['group_id'])
                      ->setName($row['group_name'])
                      ->setUpdatedAt($this->parseSQLDate($row['last_change']))
                      ->save();
                
                $lastGroupId = $row['group_id'];
            }
            
            $publication = Model\Data\PublicationQuery::create()->findOneById($row['publication_id']);
            $group->addPublication($publication);
        }
        
    }
    /* ---------------------------------------------------------------------
     * partner
     * ------------------------------------------------------------------ */
    function convertPartners($dbh) {
        
        $rawData = "SELECT * FROM partner";
            
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
            $partner->save($this->propelConnection);
        }
    }
        
      // after publications and partners
    function convertCopyLocations($dbh){
        
        $rawData = "SELECT 
                        id_Fundstellen as `copylocation_id`
                        ,book.id_book as `publication_id`
                        ,partner.id_book_locations as `partner_id`
                        ,NULLIF(fundstellen.dta_insert_date, '0000-00-00 00:00:00') as `created_at`
                        ,fundstellen.comments as `comments`
                        ,NULLIF(`accessible`, 2) as `accessible`      -- 2 is currently used for 'not clear'
                        ,fundstellen.log_last_user as `updated_by`
                        ,fundstellen.log_last_change as `updated_at`
                        ,signatur as `catalogue_signature`
                        ,bib_id as `catalogue_internal`
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
                $this->errors[] = array(
                    'message' => 'Fundstelle verweist auf nicht-existierende Publikation.'
                    ,'action' => 'Fundstelle nicht übernommen.'
                );
                continue;
            }
                
            try {

            $copyLocation = new Model\Workflow\CopyLocation();
            $copyLocation->setId($row['copylocation_id'])
                    ->setPublicationId($row['publication_id'])
                    ->setPartnerId($row['partner_id'])
                    ->setCreatedAt($row['created_at'])
                    ->setComments($row['comments'])
                    ->setAvailable($row['accessible'])
                    ->setUpdatedAt($row['updated_at'])
                    ->setCatalogueSignature($row['catalogue_signature'])
                    ->setCatalogueInternal($row['catalogue_internal']);
            $copyLocation->save($this->propelConnection);
            
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
                    'action'  => "Task $row[task_id] übersprungen",
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
            $task->save($this->propelConnection);
            
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
                    ->save($this->propelConnection);
        }
    }
    
    /* ---------------------------------------------------------------------
     * place
     * ------------------------------------------------------------------ */
         
    function convertPlaces($dbh) {
        
        $rawData = "
                SELECT DISTINCT
                    CASE location
                        WHEN 'Frankfurt a. M' THEN 'Frankfurt (Main)'
                        WHEN 'Freiburg i. Br.' THEN 'Freiburg (Breisgau)'
                        WHEN 'Halle a. S.' THEN 'Halle (Saale)'
                        WHEN 'Leipzig (fingierte Druckorte)' THEN 'Leipzig'
                        ELSE location
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
                    location";
                        
        foreach ($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key) { $value = $value === NULL ? NULL : utf8_encode($value); });
                        
            $place = new Model\Data\Place();
            $place->setName($row['location'])
                    ->save($this->propelConnection);
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
                        
            $user->save($this->propelConnection);
        }
        
    }
        
    /* ---------------------------------------------------------------------
     * author
     * ------------------------------------------------------------------ */
         
    function convertAuthors($dbh) {
        
        // merge autor1_xxx and autor2_xxx columns into one table
        // autor3_xxx is never used.
        $rawData = "SELECT  
                      id_book
                      ,firstname 
                      ,lastname 
                      ,spelling
                      ,pnd
                    FROM (
                        SELECT 
                            id_book
                            ,autor1_prename as firstname 
                            ,autor1_lastname as lastname
                            ,autor1_spelling as spelling
                            ,autor1_pnd as pnd 
                        FROM book
                        WHERE autor1_prename <> '' OR autor1_lastname <> '' OR autor1_pnd <> ''
                        UNION 
                        SELECT 
                            id_book
                            ,autor2_prename as firstname
                            ,autor2_lastname as lastname
                            ,NULL as spelling
                            ,autor2_pnd as pnd 
                        FROM book
                        WHERE autor2_prename <> '' OR autor2_lastname <> '' OR autor2_pnd <> ''
                    ) as names 
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
                    ->save($this->propelConnection);
                
            } else if($gndCollisions !== NULL && $gndCollisions->count() == 1){
                
                $publication
                    ->addPersonPublication(new Model\Master\PersonPublication($gndCollisions[0]->getId(), 'Autor'))
                    ->save($this->propelConnection);
                
            } else {
                
                // create the name object
                $name = new Model\Data\Personalname();
                if ($row['firstname'] !== NULL)
                    $name->addNamefragment(new Model\Data\Namefragment('Vorname', $row['firstname']));
                if ($row['lastname'] !== NULL)
                    $name->addNamefragment(new Model\Data\Namefragment('Nachname', $row['lastname']));
                if ($row['spelling'] !== NULL)
                    $name->addNamefragment(new Model\Data\Namefragment('Alternative Schreibweise', $row['spelling']));
                
                $person = new Model\Data\Person();
                $person->setGnd($row['pnd'])            // does nothing if pnd is NULL
                        ->addPersonalname($name)
                        ->save($this->propelConnection);
                
                $lastPerson = $person;
                $lastFirstname = $row['firstname'];
                $lastLastname  = $row['lastname'];
                
                $publication
                    ->addPersonPublication(new Model\Master\PersonPublication($person->getId(), 'Autor'))
                    ->save($this->propelConnection);
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
                person as person,
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
                        'book.id_book' => $row['person'],
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
                    ->save($this->propelConnection);
        }
    }
        
    function checkOldDatabase(\PDO $dbh) {
        

        // normally, book.year and book.dta_pub_date contain the same values in all rows.
        // check if that is still the case with the current input dump
        $checkQuery = "select id_book as book_id, `year`, `dta_pub_date` from book where `year` <> `dta_pub_date`";

        foreach ($dbh->query($checkQuery)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            
            $this->warnings[] = array(
                'year and dta_pub_date differ'=>
                $row['book_id'] . " year: $row[year] dta_pub_date: $row[dta_pub_date]"
            );
        }
        
        $checkQuery = " SELECT 
                            book.id_book, title, autor1_lastname, year, dta_seiten, type
                        FROM
                            book join metadaten on book.id_book = metadaten.id_book
                        WHERE
                            dta_seiten is not null 
                            AND type in ('M','MM','DM')";
        
        foreach ($dbh->query($checkQuery)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            
            $this->warnings[] = array(
                'seitenangabe auf publikationstyp, der dies nicht unterstützt'=>"",
                'id_book' => $row['id_book'],
                'title' => $row['title'],
                'autor' => $row['autor1_lastname'],
                'dta_seiten' => $row['dta_seiten']
            );
        }
        
        // fields that contain no data or outdated data. They are not covered by the dump conversion routines.
        $unusedFields = array(
            'book' => array(
                'autor3_spelling', 'autor2_syn_names', 'autor3_prename', 'autor3_lastname', 'autor3_spelling', 'autor3_syn_names', 'autor3_pnd', 
                'dta_quelle', 'dta_pub_name', 
                'zs_nummer', 'zs_jahrgang', 'zs_hg', 'zs_titel', 
                'type_pub', 
                'erschienen_in', 'dta_uebersetzer', 
                'first_in_autor', 'first_auflagenvermerk', 'first_publisher', 'first_verlag', 'first_reihe_jahrgang', 'first_bibl_angabe'),
            'metadaten' => array(
                'book_sides', 'dta_book_sides', 
                'log_last_change', 'log_last_user',
                'klassifikation',
                'prioritaet',
                'encoding_desc',
                'type_first'
            ),
            'open_tasks_groups' => array(
                'task_name',
                'realendtime',
                'comments',
                'physical_location',
                'parent_task',
                'active', 'activate_date',
            ),
            'open_tasks' => array(
                'task_name',
                'realendtime',
                'physical_location',
                'parent_task',
                'active', 'activate_date',
            ),
            'partner' => array(
                'adress',
                'phone1', 'phone2', 'phone3', 'fax',
                'log_last_change', 'log_last_user'
            ),
            'fundstellen' => array(
                'quality', 'source'
            ),
            'user' => array(
                'phone', 'pw', 'creation_date', 'last_use', 'last_book_id',
            ),
            'groups' => array(
                'log_last_change', 'log_last_user'
            )
        );
        
        foreach ($unusedFields as $table => $fields){
            
            foreach($fields as $field){
                
                $query = "SELECT `$field` FROM `$table` WHERE `$field` IS NOT NULL GROUP BY `$field`;";
                foreach ($dbh->query($query)->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                    $this->warnings[] = array(
                        'some values in ignored column are not null'=> "$table.$field",
                    );
                    break;
                 }
                
            }
            
        }
        
    }
    function cleanUpOldDatabase(\PDO $dbh) {
        
        // remove unused tables
        $queries[] = "DROP table `dtadb`.`corpus_use`;
                      DROP table `dtadb`.`lastusergroups`;";
        
        
        
        
        // trim all text columns and set empty strings to NULL
        foreach ($dbh->query("SHOW tables") as $row) {
            
            $relation = $row["Tables_in_" . $this->database];
            
            $getTextColumns = "SHOW COLUMNS FROM $relation WHERE 
                                `Type` LIKE 'varchar%' -- varchars of any length 
                                OR `Type` LIKE '%text' -- text and mediumtext";
            
            $trimCommands = array();
            
            foreach ($dbh->query($getTextColumns) as $col) {
                
//                $fields[$relation][] = array($col["Field"], $col["Type"]);
                $trimCommands[] = "$col[Field] = NULLIF(trim(CHAR(9) FROM trim($col[Field])),'')";
            }
            
            if(count($trimCommands) > 0){
                $updateQuery = "UPDATE $relation SET " . implode(",", $trimCommands);
                $pdoStatement = $dbh->query($updateQuery);
                $affectedRows = $pdoStatement !== false ? $pdoStatement->rowCount() : $dbh->errorInfo();

                $this->messages[] = array(
                    'message' => "All columns of table $relation trimmed. Empty strings are set to NULL.", 
                    'affected rows' => $affectedRows,
                    'query'=>$updateQuery);
            }
        }
        
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