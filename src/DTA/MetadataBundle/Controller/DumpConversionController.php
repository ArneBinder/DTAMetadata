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
    private $username = 'root';
    private $password = 'root'; //garamond4000
    private $database = "dtadb";
    private $dumpPath = '/Users/macbookdata/Dropbox/DTA/dumpConversion/dtadb_2013-09-29_07-10-01.sql';
    private $mysqlExec = '/Applications/MAMP/Library/bin/mysql';
        
    /**
     * Connects to your mysql database.
     * @param type $username MySQL access parameters.
     * @param type $password MySQL access parameters.
     * @param type $database The schema name within the database.
     * @return \PDO
     * @throws Exception
     */
    function connect($username, $password, $database) {
        $dsn = "mysql:dbname=$database;host=127.0.0.1";
        try {
            return new \PDO($dsn, $username, $password);
        } catch (\PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    function convertAction(){
        
         // import dump
//        system("$this->mysqlExec -u $this->username -p$this->password dtadb < $this->dumpPath");

        // connect to imported database
        $dbh = $this->connect($this->username, $this->password, $this->database);
        
        $t = new Model\Workflow\Task();
        
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
            echo $query . '<br/>';
            $dbh->query($query);
        }
        
        die('done');
    }
}        
?>