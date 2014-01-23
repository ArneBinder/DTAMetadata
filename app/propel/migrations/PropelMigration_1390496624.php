<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1390496624.
 * Generated on 2014-01-23 18:03:44 by macbookdata
 */
class PropelMigration_1390496624
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

ALTER TABLE "article" ADD PRIMARY KEY ("id","publication_id");

ALTER TABLE "chapter" DROP CONSTRAINT "chapter_pkey";

ALTER TABLE "chapter" ADD PRIMARY KEY ("id","publication_id");

ALTER TABLE "multi_volume" DROP CONSTRAINT "multi_volume_pkey";

ALTER TABLE "multi_volume" ADD PRIMARY KEY ("id","publication_id");

ALTER TABLE "series" DROP CONSTRAINT "series_pkey";

ALTER TABLE "series" ADD PRIMARY KEY ("id","publication_id");

ALTER TABLE "volume" DROP CONSTRAINT "volume_pkey";

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
ALTER TABLE "article" DROP CONSTRAINT "article_pkey";

ALTER TABLE "article" ADD PRIMARY KEY ("id");

ALTER TABLE "chapter" DROP CONSTRAINT "chapter_pkey";

ALTER TABLE "chapter" ADD PRIMARY KEY ("id");

ALTER TABLE "multi_volume" DROP CONSTRAINT "multi_volume_pkey";

ALTER TABLE "multi_volume" ADD PRIMARY KEY ("id");

ALTER TABLE "series" DROP CONSTRAINT "series_pkey";

ALTER TABLE "series" ADD PRIMARY KEY ("id");

ALTER TABLE "volume" DROP CONSTRAINT "volume_pkey";

ALTER TABLE "volume" ADD PRIMARY KEY ("id");
',
);
    }

}