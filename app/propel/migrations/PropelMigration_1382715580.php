<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1382715580.
 * Generated on 2013-10-25 17:39:40 by macbookdata
 */
class PropelMigration_1382715580
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
  'DTAMetadata' => '
DROP TABLE IF EXISTS "printrun" CASCADE;

DROP TABLE IF EXISTS "relatedset" CASCADE;

ALTER TABLE "partner" RENAME COLUMN "contactData" TO "contact_data";
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
  'DTAMetadata' => '
CREATE TABLE "printrun"
(
    "id" serial NOT NULL,
    "name" TEXT,
    "numeric" INTEGER,
    "numpages" INTEGER,
    "numpagesnormed" INTEGER,
    PRIMARY KEY ("id")
);

CREATE TABLE "relatedset"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

ALTER TABLE "partner" RENAME COLUMN "contact_data" TO "contactData";
',
);
    }

}