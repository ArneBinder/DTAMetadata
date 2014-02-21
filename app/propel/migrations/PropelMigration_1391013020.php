<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1391013020.
 * Generated on 2014-01-29 17:30:20 by macbookdata
 */
class PropelMigration_1391013020
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
DROP TABLE IF EXISTS "namefragmenttype" CASCADE;

DROP TABLE IF EXISTS "titlefragmenttype" CASCADE;

CREATE UNIQUE INDEX "article_U_1" ON "article" ("id");

CREATE UNIQUE INDEX "chapter_U_1" ON "chapter" ("id");

CREATE UNIQUE INDEX "multi_volume_U_1" ON "multi_volume" ("id");

CREATE UNIQUE INDEX "series_U_1" ON "series" ("id");

CREATE UNIQUE INDEX "volume_U_1" ON "volume" ("id");
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
CREATE TABLE "namefragmenttype"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE TABLE "titlefragmenttype"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

    ALTER TABLE "article" DROP CONSTRAINT "article_U_1";
    
    ALTER TABLE "chapter" DROP CONSTRAINT "chapter_U_1";
    
    ALTER TABLE "multi_volume" DROP CONSTRAINT "multi_volume_U_1";
    
    ALTER TABLE "series" DROP CONSTRAINT "series_U_1";
    
    ALTER TABLE "volume" DROP CONSTRAINT "volume_U_1";
    ',
);
    }

}