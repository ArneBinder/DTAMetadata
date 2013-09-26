<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1380191845.
 * Generated on 2013-09-26 12:37:25 by macbookdata
 */
class PropelMigration_1380191845
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
ALTER TABLE "imagesource" DROP CONSTRAINT "imagesource_FK_3";

ALTER TABLE "imagesource" ADD CONSTRAINT "imagesource_FK_3"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id")
    ON DELETE SET NULL;

ALTER TABLE "textsource" DROP CONSTRAINT "textsource_FK_3";

ALTER TABLE "textsource" ADD CONSTRAINT "textsource_FK_3"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id")
    ON DELETE SET NULL;
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
ALTER TABLE "imagesource" DROP CONSTRAINT "imagesource_FK_3";

ALTER TABLE "imagesource" ADD CONSTRAINT "imagesource_FK_3"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id");

ALTER TABLE "textsource" DROP CONSTRAINT "textsource_FK_3";

ALTER TABLE "textsource" ADD CONSTRAINT "textsource_FK_3"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id");
',
);
    }

}