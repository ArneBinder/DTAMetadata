<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1382713699.
 * Generated on 2013-10-25 17:08:19 by macbookdata
 */
class PropelMigration_1382713699
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
DROP TABLE IF EXISTS "printrun" CASCADE;

DROP TABLE IF EXISTS "relatedset" CASCADE;

ALTER TABLE "partner" RENAME COLUMN "address" TO "contactData";

ALTER TABLE "partner" RENAME COLUMN "mail" TO "contactData";

ALTER TABLE "partner" RENAME COLUMN "web" TO "contactData";

ALTER TABLE "partner" RENAME COLUMN "phone1" TO "contactData";

ALTER TABLE "partner" RENAME COLUMN "phone2" TO "contactData";

ALTER TABLE "partner" RENAME COLUMN "phone3" TO "contactData";

ALTER TABLE "partner" RENAME COLUMN "fax" TO "contactData";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_7";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_8";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_4";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_5";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_6";

ALTER TABLE "publication" RENAME COLUMN "printrun_id" TO "www_ready";

ALTER TABLE "publication" RENAME COLUMN "relatedset_id" TO "www_ready";

ALTER TABLE "publication" ADD "volume_alphanumeric" TEXT;

ALTER TABLE "publication" ADD "volume_numeric" TEXT;

ALTER TABLE "publication" ADD "volumes_total" TEXT;

ALTER TABLE "publication" ADD "numpages" INTEGER;

ALTER TABLE "publication" ADD "numpagesnormed" INTEGER;

ALTER TABLE "publication" ADD "publishingcompany_id_is_reconstructed" BOOLEAN DEFAULT \'f\';

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_4"
    FOREIGN KEY ("publicationdate_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_5"
    FOREIGN KEY ("firstpublicationdate_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_6"
    FOREIGN KEY ("font_id")
    REFERENCES "font" ("id");

ALTER TABLE "publication_dm" DROP CONSTRAINT "publication_dm_FK_2";

ALTER TABLE "publication_dm" ADD "title_id" INTEGER NOT NULL;

ALTER TABLE "publication_dm" DROP COLUMN "parent";

ALTER TABLE "publication_ja" DROP CONSTRAINT "publication_ja_FK_3";

ALTER TABLE "publication_ja" DROP CONSTRAINT "publication_ja_FK_2";

ALTER TABLE "publication_ja" ADD CONSTRAINT "publication_ja_FK_2"
    FOREIGN KEY ("volume_id")
    REFERENCES "volume" ("id");

ALTER TABLE "task" DROP CONSTRAINT "task_FK_4";

ALTER TABLE "task" ADD "partner_id" INTEGER;

ALTER TABLE "task" ADD "created_at" TIMESTAMP;

ALTER TABLE "task" ADD "updated_at" TIMESTAMP;

ALTER TABLE "task" ADD CONSTRAINT "task_FK_4"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id");
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
CREATE TABLE "printrun"
(
    "id" serial NOT NULL,
    "name" TEXT,
    "numeric" INTEGER,
    "numpages" INTEGER,
    "numpagesnormed" INTEGER,
    PRIMARY KEY ("id")
);

CREATE TABLE "relatedset"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

ALTER TABLE "partner" RENAME COLUMN "contactData" TO "address";

ALTER TABLE "partner" RENAME COLUMN "contactData" TO "mail";

ALTER TABLE "partner" RENAME COLUMN "contactData" TO "web";

ALTER TABLE "partner" RENAME COLUMN "contactData" TO "phone1";

ALTER TABLE "partner" RENAME COLUMN "contactData" TO "phone2";

ALTER TABLE "partner" RENAME COLUMN "contactData" TO "phone3";

ALTER TABLE "partner" RENAME COLUMN "contactData" TO "fax";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_4";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_5";

ALTER TABLE "publication" DROP CONSTRAINT "publication_FK_6";

ALTER TABLE "publication" RENAME COLUMN "www_ready" TO "printrun_id";

ALTER TABLE "publication" RENAME COLUMN "www_ready" TO "relatedset_id";

ALTER TABLE "publication" DROP COLUMN "volume_alphanumeric";

ALTER TABLE "publication" DROP COLUMN "volume_numeric";

ALTER TABLE "publication" DROP COLUMN "volumes_total";

ALTER TABLE "publication" DROP COLUMN "numpages";

ALTER TABLE "publication" DROP COLUMN "numpagesnormed";

ALTER TABLE "publication" DROP COLUMN "publishingcompany_id_is_reconstructed";

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

ALTER TABLE "publication_dm" ADD "parent" INTEGER;

ALTER TABLE "publication_dm" DROP COLUMN "title_id";

ALTER TABLE "publication_dm" ADD CONSTRAINT "publication_dm_FK_2"
    FOREIGN KEY ("parent")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_ja" DROP CONSTRAINT "publication_ja_FK_2";

ALTER TABLE "publication_ja" ADD CONSTRAINT "publication_ja_FK_2"
    FOREIGN KEY ("parent")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_ja" ADD CONSTRAINT "publication_ja_FK_3"
    FOREIGN KEY ("volume_id")
    REFERENCES "volume" ("id");

ALTER TABLE "task" DROP CONSTRAINT "task_FK_4";

ALTER TABLE "task" DROP COLUMN "partner_id";

ALTER TABLE "task" DROP COLUMN "created_at";

ALTER TABLE "task" DROP COLUMN "updated_at";

ALTER TABLE "task" ADD CONSTRAINT "task_FK_4"
    FOREIGN KEY ("responsibleuser_id")
    REFERENCES "dta_user" ("id");
',
);
    }

}