<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1368792315.
 * Generated on 2013-05-17 14:05:15 by carlwitt
 */
class PropelMigration_1368792315
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
ALTER TABLE "user" RENAME TO "dta_user";

ALTER TABLE "task" DROP CONSTRAINT "task_FK_4";

ALTER TABLE "task" ADD CONSTRAINT "task_FK_4"
    FOREIGN KEY ("responsibleuser_id")
    REFERENCES "dta_user" ("id");
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
ALTER TABLE "dta_user" RENAME TO "user";

ALTER TABLE "task" DROP CONSTRAINT "task_FK_4";

ALTER TABLE "task" ADD CONSTRAINT "task_FK_4"
    FOREIGN KEY ("responsibleuser_id")
    REFERENCES "user" ("id");
',
);
    }

}