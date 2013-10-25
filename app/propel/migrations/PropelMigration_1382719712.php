<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1382719712.
 * Generated on 2013-10-25 18:48:32 by macbookdata
 */
class PropelMigration_1382719712
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
ALTER TABLE "task" RENAME COLUMN "active" TO "done";

ALTER TABLE "task" DROP COLUMN "activated_date";
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
ALTER TABLE "task" RENAME COLUMN "done" TO "active";

ALTER TABLE "task" ADD "activated_date" DATE;
',
);
    }

}