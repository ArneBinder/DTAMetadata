<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1369383112.
 * Generated on 2013-05-24 10:11:52 by carlwitt
 */
class PropelMigration_1369383112
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
ALTER TABLE "task" RENAME COLUMN "start" TO "startdate";

ALTER TABLE "task" RENAME COLUMN "end" TO "startdate";

ALTER TABLE "task" ADD "enddate" DATE;
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
ALTER TABLE "task" RENAME COLUMN "startdate" TO "start";

ALTER TABLE "task" RENAME COLUMN "startdate" TO "end";

ALTER TABLE "task" DROP COLUMN "enddate";
',
);
    }

}