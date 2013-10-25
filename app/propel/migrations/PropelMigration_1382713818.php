<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1382713818.
 * Generated on 2013-10-25 17:10:18 by macbookdata
 */
class PropelMigration_1382713818
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
ALTER TABLE "partner" DROP COLUMN "mail";

ALTER TABLE "partner" DROP COLUMN "web";

ALTER TABLE "partner" DROP COLUMN "phone1";

ALTER TABLE "partner" DROP COLUMN "phone2";

ALTER TABLE "partner" DROP COLUMN "phone3";

ALTER TABLE "partner" DROP COLUMN "fax";

ALTER TABLE "publication" RENAME COLUMN "www_ready" TO "wwwReady";

ALTER TABLE "publication" RENAME COLUMN "relatedset_id" TO "wwwReady";

ALTER TABLE "task" ADD CONSTRAINT "task_FK_5"
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
ALTER TABLE "partner" ADD "mail" TEXT;

ALTER TABLE "partner" ADD "web" TEXT;

ALTER TABLE "partner" ADD "phone1" TEXT;

ALTER TABLE "partner" ADD "phone2" TEXT;

ALTER TABLE "partner" ADD "phone3" TEXT;

ALTER TABLE "partner" ADD "fax" TEXT;

ALTER TABLE "publication" RENAME COLUMN "wwwReady" TO "www_ready";

ALTER TABLE "publication" RENAME COLUMN "wwwReady" TO "relatedset_id";

ALTER TABLE "task" DROP CONSTRAINT "task_FK_5";
',
);
    }

}