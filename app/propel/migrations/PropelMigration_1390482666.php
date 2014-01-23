<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1390482666.
 * Generated on 2014-01-23 14:11:06 by macbookdata
 */
class PropelMigration_1390482666
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
ALTER TABLE "publication" ADD "legacy_DWDS_category1" TEXT;

ALTER TABLE "publication" ADD "legacy_DWDS_subcategory1" TEXT;

ALTER TABLE "publication" ADD "legacy_DWDS_category2" TEXT;

ALTER TABLE "publication" ADD "legacy_DWDS_subcategory2" TEXT;
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
ALTER TABLE "publication" DROP COLUMN "legacy_DWDS_category1";

ALTER TABLE "publication" DROP COLUMN "legacy_DWDS_subcategory1";

ALTER TABLE "publication" DROP COLUMN "legacy_DWDS_category2";

ALTER TABLE "publication" DROP COLUMN "legacy_DWDS_subcategory2";
',
);
    }

}