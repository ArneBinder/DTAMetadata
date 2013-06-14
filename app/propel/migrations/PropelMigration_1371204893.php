<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1371204893.
 * Generated on 2013-06-14 12:14:53 by carlwitt
 */
class PropelMigration_1371204893
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
ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_9";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_2";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_3";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_4";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_5";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_6";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_7";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_8";

ALTER TABLE "publication" DROP COLUMN "title_id";

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_2"
    FOREIGN KEY ("publishingcompany_id")
    REFERENCES "publishingcompany" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_3"
    FOREIGN KEY ("place_id")
    REFERENCES "place" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_4"
    FOREIGN KEY ("printrun_id")
    REFERENCES "printrun" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_5"
    FOREIGN KEY ("relatedset_id")
    REFERENCES "relatedset" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_6"
    FOREIGN KEY ("publicationdate_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_7"
    FOREIGN KEY ("firstpublicationdate_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_8"
    FOREIGN KEY ("font_id")
    REFERENCES "font" ("id");

ALTER TABLE "work" ADD CONSTRAINT "work_FK_2"
    FOREIGN KEY ("datespecification_id")
    REFERENCES "datespecification" ("id");
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
ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_2";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_3";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_4";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_5";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_6";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_7";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_8";

ALTER TABLE "publication" ADD "title_id" INTEGER NOT NULL;

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_2"
    FOREIGN KEY ("title_id")
    REFERENCES "title" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_3"
    FOREIGN KEY ("publishingcompany_id")
    REFERENCES "publishingcompany" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_4"
    FOREIGN KEY ("place_id")
    REFERENCES "place" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_5"
    FOREIGN KEY ("printrun_id")
    REFERENCES "printrun" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_6"
    FOREIGN KEY ("relatedset_id")
    REFERENCES "relatedset" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_7"
    FOREIGN KEY ("publicationdate_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_8"
    FOREIGN KEY ("firstpublicationdate_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_9"
    FOREIGN KEY ("font_id")
    REFERENCES "font" ("id");

ALTER TABLE "work" DROP CONSTRAINT "work_FK_2";
',
);
    }

}