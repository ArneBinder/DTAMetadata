<html>
    <head>
        <title>Find unused attributes</title>
        <?php
        
        /**
         * @author Carl Witt <carl.witt@fu-berlin.de>
         * The purpose of this script is to find unused attributes in the relations of a MySQL schema.
         * It will output MySQL statements to remove unwanted columns which can be selected in the browser.
         */
        
        /**
         * Configure access to the database.
         */
        $username = 'root';
        $password = 'root'; //garamond4000
        $database = "dtadb";
        
        /**
         * a column must have at least MIN_COLUMN_VALUES different values to count as "normal" column.
         * Use a value of three to find columns with only NULL and '' values for example.
         */
        define('MIN_COLUMN_VALUES', 500000);

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
                return new PDO($dsn, $username, $password);
            } catch (PDOException $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }

        /**
         * Creates a query that returns the different values and their respective count for a given attribute in a given relation.
         * @param type $relation The table name.
         * @param string $attribute The column name.
         * @return String MySQL query
         */
        function countQuery($relation, $attribute) {
            $attribute = "`" . $attribute . "`";
            $differentValues = "
            SELECT 
                *
            FROM
                (SELECT 
                    if($attribute IS NULL, '&lt;NULL&gt;', 
                        if(CAST($attribute AS CHAR) = '', '&lt;EMPTY STRING&gt;', $attribute)) AS `value`,
                    COUNT(*) as `count`
                FROM
                    $relation
                GROUP BY $attribute) valueRange
            WHERE TRUE
                -- AND valueRange.value IS NOT NULL 
                -- AND valueRange.value <> ''  
            ";
//            var_dump($differentValues);
            return $differentValues;
        }
        ?>

        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script type="text/javascript"
            src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

        <script type="text/javascript">
            <?php
            echo "var databaseName = '$database';";
            ?>
            function deleteColumn(relationName, columnName){
                return "ALTER TABLE `"+ databaseName +"`.`"+ relationName +"` DROP COLUMN `"+ columnName +"`;";
            }
            function assembleQuery(columns){
                var result = "";
                for(colIdx in columns){
                    var relationName = columns[colIdx].split(".")[0];
                    var columnName = columns[colIdx].split(".")[1];
                    result += deleteColumn(relationName, columnName);

                }
                return result;
            }
            $(document).ready(function(){
                $("#generate").click(function(){
                    var deleteColumns = []
                    jQuery("input:checked").each(function(){
                        deleteColumns.push($(this).attr("id"));
                    });
                    jQuery("<p/>").text(assembleQuery(deleteColumns)).prependTo($("body"));
                });
                $( "#accordion" ).accordion({
                    collapsible: true,
                    heightStyle: "content"
                });
            });
        </script>

    </head>
    <body>
        <!-- Generate Result Button -->
        <input type="submit" id="generate" value="generate cleaner query"/>
        
        <div id="accordion">
        <?php
        $dbh = connect($username, $password, $database);
        
        $totalFields = 0;
        $suspectiveFields = 0;
        
        foreach ($dbh->query("SHOW tables") as $row) {
            
            $relation = $row["Tables_in_" . $database];
            
            foreach ($dbh->query("SHOW COLUMNS FROM " . $relation) as $col) {
                
                $totalFields++;
                $field = $col["Field"];

                $vals = $dbh->query(countQuery($relation, $field));
                if ($vals)
                    $vals = $vals->fetchAll();
                else
                    print countQuery($relation, $field);

                $differentVals = count($vals);

                if ($differentVals < MIN_COLUMN_VALUES) {
                    $suspectiveFields++;
                    print "<h2>";
                    // the id of the checkbox will later be used to parse out the relation and attribute.
                    echo '<input type="checkbox" id="' . $relation . '.' . $field . '"/>';
                    print "$relation.$field: $differentVals distinct values </h2>";
                    // output the different values and their respective count
                    echo "<p>";
                    foreach ($vals as $row) {
                        print htmlentities(utf8_encode($row["value"])) ;
                        print " ($row[count])" . "<br/>";
                    }
                    echo "</p>";
                }
            }
        }
        // short summary of the schema.
        print "<hr/>";
        print "Total fields in $database: $totalFields.<br/>";
        print "$suspectiveFields columns with less than ".MIN_COLUMN_VALUES." different values detected.";
        ?>
        </div>
    </body>
</html>