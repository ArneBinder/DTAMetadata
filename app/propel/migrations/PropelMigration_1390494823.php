<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1390494823.
 * Generated on 2014-01-23 17:33:43 by macbookdata
 */
class PropelMigration_1390494823
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
DROP TABLE IF EXISTS "sequence_entry" CASCADE;

ALTER TABLE "article" DROP CONSTRAINT "article_pkey";

ALTER TABLE "article" ADD "id" serial NOT NULL;

ALTER TABLE "article" ADD PRIMARY KEY ("id","publication_id");

ALTER TABLE "chapter" DROP CONSTRAINT "chapter_pkey";

ALTER TABLE "chapter" ADD "id" serial NOT NULL;

ALTER TABLE "chapter" ADD PRIMARY KEY ("id","publication_id");

ALTER TABLE "multi_volume" DROP CONSTRAINT "multi_volume_pkey";

ALTER TABLE "multi_volume" ADD "id" serial NOT NULL;

ALTER TABLE "multi_volume" ADD PRIMARY KEY ("id","publication_id");

ALTER TABLE "series" DROP CONSTRAINT "series_pkey";

ALTER TABLE "series" ADD "id" serial NOT NULL;

ALTER TABLE "series" ADD "issue" TEXT;

ALTER TABLE "series" ADD "volume" TEXT;

ALTER TABLE "series" ADD PRIMARY KEY ("id","publication_id");

ALTER TABLE "volume" DROP CONSTRAINT "volume_pkey";

ALTER TABLE "volume" ADD "id" serial NOT NULL;

ALTER TABLE "volume" ADD PRIMARY KEY ("id","publication_id");
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
CREATE TABLE "sequence_entry"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "sequence_id" TEXT,
    "sequence_name" TEXT,
    "sequence_type" INT2 DEFAULT 0,
    "title_id" INTEGER NOT NULL,
    "sortable_rank" INTEGER,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE INDEX "sequence_entry_I_1" ON "sequence_entry" ("sequence_id");

ALTER TABLE "article" DROP CONSTRAINT "article_pkey";

ALTER TABLE "article" DROP COLUMN "id";

ALTER TABLE "article" ADD PRIMARY KEY ("publication_id");

ALTER TABLE "chapter" DROP CONSTRAINT "chapter_pkey";

ALTER TABLE "chapter" DROP COLUMN "id";

ALTER TABLE "chapter" ADD PRIMARY KEY ("publication_id");

ALTER TABLE "multi_volume" DROP CONSTRAINT "multi_volume_pkey";

ALTER TABLE "multi_volume" DROP COLUMN "id";

ALTER TABLE "multi_volume" ADD PRIMARY KEY ("publication_id");

ALTER TABLE "series" DROP CONSTRAINT "series_pkey";

ALTER TABLE "series" DROP COLUMN "id";

ALTER TABLE "series" DROP COLUMN "issue";

ALTER TABLE "series" DROP COLUMN "volume";

ALTER TABLE "series" ADD PRIMARY KEY ("publication_id");

ALTER TABLE "volume" DROP CONSTRAINT "volume_pkey";

ALTER TABLE "volume" DROP COLUMN "id";

ALTER TABLE "volume" ADD PRIMARY KEY ("publication_id");
',
);
    }

}