<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1380116485.
 * Generated on 2013-09-25 15:41:25 by macbookdata
 */
class PropelMigration_1380116485
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
ALTER TABLE "imagesource" ADD "faksimileRefRange" TEXT;

ALTER TABLE "imagesource" ADD "originalRefRange" TEXT;

ALTER TABLE "imagesource" DROP COLUMN "numpages";
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
ALTER TABLE "imagesource" ADD "numpages" INTEGER;

ALTER TABLE "imagesource" DROP COLUMN "faksimileRefRange";

ALTER TABLE "imagesource" DROP COLUMN "originalRefRange";
',
);
    }

}