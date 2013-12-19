<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1387458894.
 * Generated on 2013-12-19 14:14:54 by macbookdata
 */
class PropelMigration_1387458894
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
  'dtametadata' => '
DROP TABLE IF EXISTS "publication_dm" CASCADE;

DROP TABLE IF EXISTS "publication_ds" CASCADE;

DROP TABLE IF EXISTS "publication_j" CASCADE;

DROP TABLE IF EXISTS "publication_ja" CASCADE;

DROP TABLE IF EXISTS "publication_m" CASCADE;

DROP TABLE IF EXISTS "publication_mms" CASCADE;

DROP TABLE IF EXISTS "publication_ms" CASCADE;

DROP TABLE IF EXISTS "series" CASCADE;
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
  'dtametadata' => '
CREATE TABLE "publication_dm"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "title_id" INTEGER NOT NULL,
    "pages" TEXT,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE TABLE "publication_ds"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "pages" TEXT,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE TABLE "publication_j"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "edition" TEXT,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE TABLE "publication_ja"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "parent" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE TABLE "publication_m"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE TABLE "publication_mms"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE TABLE "publication_ms"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "volumenumberinseries" TEXT,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE TABLE "series"
(
    "id" serial NOT NULL,
    "title_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

ALTER TABLE "publication_ds" ADD CONSTRAINT "publication_ds_FK_2"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");

ALTER TABLE "publication_mms" ADD CONSTRAINT "publication_mms_FK_2"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");

ALTER TABLE "publication_ms" ADD CONSTRAINT "publication_ms_FK_2"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");
',
);
    }

}