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
    private $mysqlExec = '/Applications/MAMP/Library/bin/mysql'; // for importing the dump
        
    /**
     * Connects to your mysql database.
     * @param type $username MySQL access parameters.
     * @param type $password MySQL access parameters.
     * @param type $database The schema name within the database.
     * @return \PDO
     * @throws Exception
     */
    function connect() {
        $dsn = "mysql:dbname=".$this->database.";host=127.0.0.1";
        try {
            return new \PDO($dsn, $this->username, $this->password);
        } catch (\PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    function nearDuplicateProposalAction(){
        
        $dbh = $this->connect();
        
//        $personsQ = "
//            SELECT * FROM
//                (SELECT TRIM(autor1_prename) as 'fn', TRIM(autor1_lastname) as 'ln' FROM book WHERE autor1_prename <> ''
//                UNION
//                SELECT TRIM(autor2_prename) as 'fn', TRIM(autor2_lastname) as 'ln' FROM book WHERE autor2_prename <> '')
//                as pcs";
//        
//        $firstNames = $dbh->query($personsQ)->fetchAll(\PDO::FETCH_COLUMN, 0);
//        $lastNames  = $dbh->query($personsQ)->fetchAll(\PDO::FETCH_COLUMN, 1);
        
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
        for($i=0; $i<count($array); $i++){
            for($j=$i; $j<count($array); $j++){
//                echo "$j <br/>";
                $similarity = -1;
                similar_text($array[$i], $array[$j], $similarity);
                if($similarity > 70 && $array[$i] != $array[$j]) {
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
    
    function nearDuplicateProposalAction_PublicationCompany(){
        $array = array("Ackermann", "Agentur des Rauhen Hauses", "Akademie der Wissenschaften", "Akademische Buchhandlung", "Alberti", "Allgemeinen Sport-Zeitung", "Amelang", "Andreä", "Arnold", "Aschendorff", "Asher & Co.", "Barth", "Barthol", "Bassermann", "Bauer", "Baumgärtner", "Beck", "Bibliographisches Institut", "Bildungs-Gesellschaft für Arbeiter", "Blätter für die Kunst", "Bohn", "Bonz", "Bornträger", "Braumüller", "Braun", "Braun und Schneider", "Breitkopf & Härtel", "Klemm", "Breitkopf und Härtel", "Brockhaus", "Brockhaus & Avenarius, Schrag", "Brunet", "Buchhandlung der Erziehungsanstalt", "Buchhandlung des Waisenhauses", "Bädeker", "Böhlau", "Cassirer", "Christmann", "Cohen", "Conrad", "Coppenrath", "Cosmos", "Costenoble", "Cotta", "Cotta; Kröner", "Craz", "Creutz", "Curt", "Decker", "Deichert", "Diederichs", "Dienemann", "Dieterich", "Dieterichsche Buchhandlung", "Dietz", "Dorn", "Duncker", "Duncker & Humblot", "Duncker und Humblot", "Dyk", "Dümmler", "Elischer", "Engelhardt", "Engelhorn", "Engelmann", "Enke", "Entsch", "Ernst", "Ernst & Korn", "Ettinger", "Fehsenfeld", "Felix", "Fischer", "Flammer und Hoffmann", "Fleischer", "Fontane", "Fontane & Co.", "Franckh", "Fricke", "Friedrich", "Fritzsch", "Frommann", "Frölich", "Fues", "Gaertner", "Gerold", "Gerstenberg", "Giel", "Giesecke & Devrient", "Goebhardt", "Groos", "Grote", "Groß", "Grädener & Richter", "Gräff", "Gräffer", "Guilhauman", "Guttentag", "Göschen", "Haack", "Habel", "Haering", "Haessel", "Hahn", "Hallberger", "Hammerich", "Hartknoch", "Hartleben", "Haude und Spener", "Heckenast", "Heckenast; Wigand", "Heinrichshofen", "Helwing", "Henne", "Hermann", "Hertz", "Hesse", "Heyer", "Heyne", "Hilscher", "Himburg", "Hirschfeld", "Hirschwald", "Hirzel", "Hitzig", "Hoff", "Hoffmann", "Hoffmann und Campe", "Hofmann", "Hrsg. d. Deutschen Zeitung; Göschen", "Huth", "Hölder", "Hölscher", "in Commission in der J. C. Hermannschen Buchhandlung", "Industrie-Comptoir", "Insel", "Issleib", "Janke", "Jent & Gaßmann", "Junius", "Kayser", "Keil", "Klostermann", "Knapp", "Korn", "Krabbe", "Kreidel", "Kriele", "Kummer", "Künast", "Lagarde und Friederich", "Landgraf", "Langen", "Laupp", "Lentner", "Levysohn", "Lewy", "Liesching", "Literarische Anstalt", "Literarisches Comptoir", "Literarisches Institut", "Loewes", "Logier", "Lorck", "Lucas", "Lüderitz", "Lüstenöder", "Löwenthal", "Macklot", "Mallinckrodt", "Matzdorff", "Maurer", "Max", "Mayer", "Meidinger", "Meissner", "Meißner", "Mendelssohn", "Meyer", "Michaelis und Bispink", "Mittler", "Mohr", "Mohr u: Zimmer", "Mohr und Zimmer", "Mühlmann", "Mylius", "Mäcken", "Naturforschender Verein", "Nauck", "Naumann", "Neff", "Nicolai", "Nicolovius", "Niemeyer", "Normalschul-Buchdruckerey", "Oldenbourg", "Oppenheim", "Orell", "Orell, Gessner, Füssli", "Osiander", "Paetel", "Palm", "Parey", "Perrenon", "Perthes", "Perthes und Besser", "Pfeffer", "Rackhorst", "Realschulbuchhandlung", "Reimarus", "Reimer", "Rein", "Reissner", "Richter", "Riemann", "Rosner", "Rücker", "Rümpler; Grimpe", "Röwer", "Sander", "Sassenbach", "Sauerländer", "Schade", "Schaub", "Schickhardt & Ebner", "Schmeitzner", "Schmerber", "Schmidt", "Schmorl & von Seefeld", "Schott", "Schrag", "Schroedel & Simon", "Schroeder", "Schul-Buchandlung", "Schulbuchhandlung", "Schultze", "Schuster & Loeffler", "Schwan", "Schweighauser", "Schweizerbart", "Schwers", "Schwetschke", "Seemann", "Selbstverlag", "Seydel", "Siemens", "Sollinger", "Spemann", "Springer", "Spurny", "Stage", "Stahel", "Steffens", "Strauß", "Tauchnitz", "Tempsky", "Teubner", "Toeche; Pormetter", "Trewendt", "Trübner", "Unger", "Unzer", "Urania", "Vaillant-Carmanne", "Vandenhoeck", "Vandenhoeck und Ruprecht", "Vandenhoek und Ruprecht", "Veit", "Veit & Co.", "Velhagen & Klasing", "Velhagen und Klasing", "Vereins-Buchhandlung", "Vereinsbuchhandlung", "Verlag der Instituts-Buchh. und in Commission bei Crusius ", "Verlag der Volksbuchhandlung", "Verlag des Bibliographischen Bureaus", "Verlags-Magazin", "Vieweg", "Vogel", "Voigt", "Voss", "Voß", "Wagner", "Wallishauser", "Wallishauser", "Wallishausser", "Walther", "Weber", "Weidmann", "Weidmann Erben und Reich", "Weidmanns Erben und Reich", "Weigel und Schneider", "Weise", "Weiß", "Wiegand, Hempel & Parsey", "Wiegandt, Hempel & Parsey", "Wiener Verlag", "Wigand", "Wilmans", "Winter", "Metzler");
        
        $candidates = array();
        for($i=0; $i<count($array); $i++){
            for($j=$i+1; $j<count($array); $j++){
                $similarity = -1;
                similar_text($array[$i], $array[$j], $similarity);
                if($similarity > 80 && $array[$i] != $array[$j]) {
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
    
    function convertAction(){
        
         // import dump
        system("$this->mysqlExec -u $this->username -p$this->password dtadb < $this->dumpPath");

        // connect to imported database
        $dbh = $this->connect();
        
        // remove some 
        $this->cleanUpOldDatabase($dbh);
        
        /* ---------------------------------------------------------------------
         * partner
         * ------------------------------------------------------------------ */
        
        $rawData = "SELECT * FROM partner";
        
        $partner;
        foreach($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row){
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key){$value = utf8_encode($value); }); 
            
            $partner = new Model\Workflow\Partner();
            $partner->setLegacyPartnerId($row['id_book_locations'])
                    ->setName($row['name'])
                    ->setContactPerson($row['person'])
                    ->setMail($row['mail'])
                    ->setWeb($row['web'])
                    ->setContactdata('Telefon: ' . $row['phone1'] . "\nAdresse: " . $row['adress'])
                    ->setComments($row['comments']);
            $partner->save();
        }
        
        /* ---------------------------------------------------------------------
         * user
         * ------------------------------------------------------------------ */
        
        $rawData = "SELECT * FROM user";
        
        $user;
        foreach($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row){
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key){$value = utf8_encode($value); }); 
            
        
            $user = new Model\Master\DtaUser();
            
            // password encryption
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $user->setSalt(md5(rand(-1239432,23429304)));
            $saltedPassword = $encoder->encodePassword('$dta010', $user->getSalt());

            // 
            var_dump($row);
            $user->setLegacyUserId($row['id_user'])
                    ->setUsername($row['name'])
                    ->setMail($row['mail'])
                    ->setPassword($saltedPassword);
            
            $user->save();
        }
        
        /* ---------------------------------------------------------------------
         * author
         * ------------------------------------------------------------------ */
        
        $rawData = "SELECT  FROM user";
        
        $user;
        foreach($dbh->query($rawData)->fetchAll(\PDO::FETCH_ASSOC) as $row){
            // encode all data from the old database as UTF8
            array_walk($row, function(&$value, $key){$value = utf8_encode($value); }); 
            
        
            $user = new Model\Master\DtaUser();
            
            // password encryption
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $user->setSalt(md5(rand(-1239432,23429304)));
            $saltedPassword = $encoder->encodePassword('$dta010', $user->getSalt());

            // 
            var_dump($row);
            $user->setLegacyUserId($row['id_user'])
                    ->setUsername($row['name'])
                    ->setMail($row['mail'])
                    ->setPassword($saltedPassword);
            
            $user->save();
        }
        
        die('done');
    }
    
    function cleanUpOldDatabase($dbh){
        
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
    }
}        
?>