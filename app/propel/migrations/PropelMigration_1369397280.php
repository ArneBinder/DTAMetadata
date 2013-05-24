<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1369397280.
 * Generated on 2013-05-24 14:08:00 by carlwitt
 */
class PropelMigration_1369397280
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
ALTER TABLE "personrole" ALTER COLUMN "applicable_to_publication" DROP NOT NULL;

ALTER TABLE "personrole" ALTER COLUMN "applicable_to_work" DROP NOT NULL;

ALTER TABLE "task" DROP COLUMN "end";

ALTER TABLE "work" ADD "title_id" INTEGER NOT NULL;

ALTER TABLE "work" ADD CONSTRAINT "work_FK_2"
    FOREIGN KEY ("title_id")
    REFERENCES "title" ("id");
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
ALTER TABLE "personrole" ALTER COLUMN "applicable_to_publication" SET NOT NULL;

ALTER TABLE "personrole" ALTER COLUMN "applicable_to_work" SET NOT NULL;

ALTER TABLE "task" ADD "end" DATE;

ALTER TABLE "work" DROP CONSTRAINT "work_FK_2";

ALTER TABLE "work" DROP COLUMN "title_id";
',
);
    }

}