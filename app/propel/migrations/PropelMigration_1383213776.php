<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1383213776.
 * Generated on 2013-10-31 11:02:56 by macbookdata
 */
class PropelMigration_1383213776
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
  'dtametadata' => '
CREATE TABLE "copy_location"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "partner_id" INTEGER NOT NULL,
    "signature" TEXT NOT NULL,
    "availability" BOOLEAN,
    "comments" TEXT,
    "legacy_fundstellen_id" INTEGER,
    PRIMARY KEY ("id")
);
',
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
  'dtametadata' => '
DROP TABLE IF EXISTS "copy_location" CASCADE;
',
);
    }

}