<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1382717968.
 * Generated on 2013-10-25 18:19:28 by macbookdata
 */
class PropelMigration_1382717968
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
ALTER TABLE "task" RENAME COLUMN "startdate" TO "start_date";

ALTER TABLE "task" RENAME COLUMN "enddate" TO "start_date";

ALTER TABLE "task" ADD "end_date" DATE;

ALTER TABLE "task" ADD "activated_date" DATE;
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
ALTER TABLE "task" RENAME COLUMN "start_date" TO "startdate";

ALTER TABLE "task" RENAME COLUMN "start_date" TO "enddate";

ALTER TABLE "task" DROP COLUMN "end_date";

ALTER TABLE "task" DROP COLUMN "activated_date";
',
);
    }

}