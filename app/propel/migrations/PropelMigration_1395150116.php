<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1395150116.
 * Generated on 2014-03-18 14:41:56 by macbookdata
 */
class PropelMigration_1395150116
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'dtametadata' => 
            "CREATE SEQUENCE publication_seq_id INCREMENT 1 NO CYCLE;                       -- create sequence
             ALTER TABLE publication ALTER id SET DEFAULT nextval('publication_seq_id');    -- add default value for id column
             ALTER SEQUENCE publication_seq_id OWNED BY publication.id;                     -- declare dependency of sequence to column (if moved or deleted)
             SELECT setval('publication_seq_id', max(id)+1) FROM publication;               -- make sequence start after highest value in publications"              
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'dtametadata' => '',
);
    }

}