<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1370608218.
 * Generated on 2013-06-07 14:30:18 by carlwitt
 */
class PropelMigration_1370608218
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
ALTER TABLE "publication" ADD "partner_id" INTEGER;

ALTER TABLE "work" DROP CONSTRAINT "work_FK_2";

ALTER TABLE "work" DROP CONSTRAINT "work_FK_1";

ALTER TABLE "work" ADD CONSTRAINT "work_FK_1"
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
ALTER TABLE "publication" DROP COLUMN "partner_id";

ALTER TABLE "work" DROP CONSTRAINT "work_FK_1";

ALTER TABLE "work" ADD CONSTRAINT "work_FK_1"
    FOREIGN KEY ("datespecification_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "work" ADD CONSTRAINT "work_FK_2"
    FOREIGN KEY ("title_id")
    REFERENCES "title" ("id");
',
);
    }

}