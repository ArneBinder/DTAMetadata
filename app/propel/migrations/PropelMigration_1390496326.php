<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1390496326.
 * Generated on 2014-01-23 17:58:46 by macbookdata
 */
class PropelMigration_1390496326
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
ALTER TABLE "article" DROP CONSTRAINT "article_pkey";

ALTER TABLE "article" ADD PRIMARY KEY ("id");

ALTER TABLE "chapter" DROP CONSTRAINT "chapter_pkey";

ALTER TABLE "chapter" ADD PRIMARY KEY ("id");

ALTER TABLE "multi_volume" DROP CONSTRAINT "multi_volume_pkey";

ALTER TABLE "multi_volume" ADD PRIMARY KEY ("id");

ALTER TABLE "series" DROP CONSTRAINT "series_pkey";

ALTER TABLE "series" ADD PRIMARY KEY ("id");

ALTER TABLE "series_publication" DROP CONSTRAINT "series_publication_FK_2";

ALTER TABLE "series_publication" DROP CONSTRAINT "series_publication_FK_1";

ALTER TABLE "series_publication" ADD CONSTRAINT "series_publication_FK_1"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");

ALTER TABLE "volume" DROP CONSTRAINT "volume_pkey";

ALTER TABLE "volume" ADD PRIMARY KEY ("id");
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
ALTER TABLE "article" DROP CONSTRAINT "article_pkey";

ALTER TABLE "article" ADD PRIMARY KEY ("publication_id","id");

ALTER TABLE "chapter" DROP CONSTRAINT "chapter_pkey";

ALTER TABLE "chapter" ADD PRIMARY KEY ("publication_id","id");

ALTER TABLE "multi_volume" DROP CONSTRAINT "multi_volume_pkey";

ALTER TABLE "multi_volume" ADD PRIMARY KEY ("publication_id","id");

ALTER TABLE "series" DROP CONSTRAINT "series_pkey";

ALTER TABLE "series" ADD PRIMARY KEY ("publication_id");

ALTER TABLE "series_publication" DROP CONSTRAINT "series_publication_FK_1";

ALTER TABLE "series_publication" ADD CONSTRAINT "series_publication_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "series_publication" ADD CONSTRAINT "series_publication_FK_2"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("publication_id");

ALTER TABLE "volume" DROP CONSTRAINT "volume_pkey";

ALTER TABLE "volume" ADD PRIMARY KEY ("publication_id","id");
',
);
    }

}