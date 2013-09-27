<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1380297919.
 * Generated on 2013-09-27 18:05:19 by macbookdata
 */
class PropelMigration_1380297919
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
DROP TABLE IF EXISTS "category_version" CASCADE;

DROP TABLE IF EXISTS "category_work_version" CASCADE;

DROP TABLE IF EXISTS "datespecification_version" CASCADE;

DROP TABLE IF EXISTS "dta_user_version" CASCADE;

DROP TABLE IF EXISTS "font_version" CASCADE;

DROP TABLE IF EXISTS "genre_version" CASCADE;

DROP TABLE IF EXISTS "genre_work_version" CASCADE;

DROP TABLE IF EXISTS "imagesource_version" CASCADE;

DROP TABLE IF EXISTS "language_version" CASCADE;

DROP TABLE IF EXISTS "language_work_version" CASCADE;

DROP TABLE IF EXISTS "license_version" CASCADE;

DROP TABLE IF EXISTS "namefragment_version" CASCADE;

DROP TABLE IF EXISTS "namefragmenttype_version" CASCADE;

DROP TABLE IF EXISTS "partner_version" CASCADE;

DROP TABLE IF EXISTS "person_publication_version" CASCADE;

DROP TABLE IF EXISTS "person_version" CASCADE;

DROP TABLE IF EXISTS "person_work_version" CASCADE;

DROP TABLE IF EXISTS "personalname_version" CASCADE;

DROP TABLE IF EXISTS "personrole_version" CASCADE;

DROP TABLE IF EXISTS "place_version" CASCADE;

DROP TABLE IF EXISTS "printrun_version" CASCADE;

DROP TABLE IF EXISTS "publication_dm_version" CASCADE;

DROP TABLE IF EXISTS "publication_ds_version" CASCADE;

DROP TABLE IF EXISTS "publication_j_version" CASCADE;

DROP TABLE IF EXISTS "publication_ja_version" CASCADE;

DROP TABLE IF EXISTS "publication_m_version" CASCADE;

DROP TABLE IF EXISTS "publication_mm_version" CASCADE;

DROP TABLE IF EXISTS "publication_mms_version" CASCADE;

DROP TABLE IF EXISTS "publication_ms_version" CASCADE;

DROP TABLE IF EXISTS "publication_publicationgroup_version" CASCADE;

DROP TABLE IF EXISTS "publication_version" CASCADE;

DROP TABLE IF EXISTS "publicationgroup_version" CASCADE;

DROP TABLE IF EXISTS "publishingcompany_version" CASCADE;

DROP TABLE IF EXISTS "relatedset_version" CASCADE;

DROP TABLE IF EXISTS "series_version" CASCADE;

DROP TABLE IF EXISTS "tag_version" CASCADE;

DROP TABLE IF EXISTS "task_version" CASCADE;

DROP TABLE IF EXISTS "tasktype_version" CASCADE;

DROP TABLE IF EXISTS "textsource_version" CASCADE;

DROP TABLE IF EXISTS "title_version" CASCADE;

DROP TABLE IF EXISTS "titlefragment_version" CASCADE;

DROP TABLE IF EXISTS "titlefragmenttype_version" CASCADE;

DROP TABLE IF EXISTS "volume_version" CASCADE;

DROP TABLE IF EXISTS "work_tag_version" CASCADE;

DROP TABLE IF EXISTS "work_version" CASCADE;

ALTER TABLE "publication_dm" DROP CONSTRAINT "publication_dm_FK_2";

ALTER TABLE "publication_dm" ADD "title_id" INTEGER NOT NULL;

ALTER TABLE "publication_dm" DROP COLUMN "parent";

ALTER TABLE "publication_ja" DROP CONSTRAINT "publication_ja_FK_3";

ALTER TABLE "publication_ja" DROP CONSTRAINT "publication_ja_FK_2";

ALTER TABLE "publication_ja" ADD CONSTRAINT "publication_ja_FK_2"
    FOREIGN KEY ("volume_id")
    REFERENCES "volume" ("id");
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
CREATE TABLE "category_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "category_work_ids" TEXT,
    "category_work_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "category_work_version"
(
    "category_id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    "id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "category_id_version" INTEGER DEFAULT 0,
    "work_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "datespecification_version"
(
    "id" INTEGER NOT NULL,
    "year" INTEGER,
    "comments" TEXT,
    "year_is_reconstructed" BOOLEAN DEFAULT \'f\',
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_ids" TEXT,
    "publication_versions" TEXT,
    "work_ids" TEXT,
    "work_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "dta_user_version"
(
    "username" TEXT,
    "password" VARCHAR(512),
    "salt" VARCHAR(512),
    "mail" TEXT,
    "phone" TEXT,
    "admin" BOOLEAN DEFAULT \'f\',
    "id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "task_ids" TEXT,
    "task_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "font_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_ids" TEXT,
    "publication_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "genre_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "childof" INTEGER,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "childof_version" INTEGER DEFAULT 0,
    "genre_ids" TEXT,
    "genre_versions" TEXT,
    "genre_work_ids" TEXT,
    "genre_work_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "genre_work_version"
(
    "genre_id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    "id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "genre_id_version" INTEGER DEFAULT 0,
    "work_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "imagesource_version"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "partner_id" INTEGER,
    "cataloguesignature" TEXT,
    "catalogueurl" TEXT,
    "numfaksimiles" INTEGER,
    "extentasofcatalogue" TEXT,
    "faksimilerefrange" TEXT,
    "originalrefrange" TEXT,
    "imageurl" TEXT,
    "imageurn" TEXT,
    "license_id" INTEGER,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_id_version" INTEGER DEFAULT 0,
    "license_id_version" INTEGER DEFAULT 0,
    "partner_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "language_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "language_work_ids" TEXT,
    "language_work_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "language_work_version"
(
    "language_id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    "id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "language_id_version" INTEGER DEFAULT 0,
    "work_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "license_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "url" TEXT,
    "applicable_to_image" BOOLEAN DEFAULT \'f\' NOT NULL,
    "applicable_to_text" BOOLEAN DEFAULT \'f\' NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "imagesource_ids" TEXT,
    "imagesource_versions" TEXT,
    "textsource_ids" TEXT,
    "textsource_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "namefragment_version"
(
    "id" INTEGER NOT NULL,
    "personalname_id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "namefragmenttypeid" INTEGER NOT NULL,
    "sortable_rank" INTEGER,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "namefragmenttypeid_version" INTEGER DEFAULT 0,
    "personalname_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "namefragmenttype_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "namefragment_ids" TEXT,
    "namefragment_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "partner_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT,
    "address" TEXT,
    "person" TEXT,
    "mail" TEXT,
    "web" TEXT,
    "comments" TEXT,
    "phone1" TEXT,
    "phone2" TEXT,
    "phone3" TEXT,
    "fax" TEXT,
    "is_organization" BOOLEAN DEFAULT \'f\',
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "imagesource_ids" TEXT,
    "imagesource_versions" TEXT,
    "textsource_ids" TEXT,
    "textsource_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "person_publication_version"
(
    "personrole_id" INTEGER NOT NULL,
    "person_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "personrole_id_version" INTEGER DEFAULT 0,
    "person_id_version" INTEGER DEFAULT 0,
    "publication_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "person_version"
(
    "id" INTEGER NOT NULL,
    "gnd" VARCHAR(255),
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "personalname_ids" TEXT,
    "personalname_versions" TEXT,
    "person_publication_ids" TEXT,
    "person_publication_versions" TEXT,
    "person_work_ids" TEXT,
    "person_work_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "person_work_version"
(
    "person_id" INTEGER NOT NULL,
    "personrole_id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    "id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "person_id_version" INTEGER DEFAULT 0,
    "personrole_id_version" INTEGER DEFAULT 0,
    "work_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "personalname_version"
(
    "id" INTEGER NOT NULL,
    "person_id" INTEGER NOT NULL,
    "sortable_rank" INTEGER,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "person_id_version" INTEGER DEFAULT 0,
    "namefragment_ids" TEXT,
    "namefragment_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "personrole_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "applicable_to_publication" BOOLEAN DEFAULT \'f\',
    "applicable_to_work" BOOLEAN DEFAULT \'f\',
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "person_publication_ids" TEXT,
    "person_publication_versions" TEXT,
    "person_work_ids" TEXT,
    "person_work_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "place_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "gnd" VARCHAR(255),
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_ids" TEXT,
    "publication_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "printrun_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT,
    "numeric" INTEGER,
    "numpages" INTEGER,
    "numpagesnormed" INTEGER,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_ids" TEXT,
    "publication_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publication_dm_version"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "parent" INTEGER,
    "pages" TEXT,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_id_version" INTEGER DEFAULT 0,
    "parent_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publication_ds_version"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "volume_id" INTEGER NOT NULL,
    "pages" TEXT,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_id_version" INTEGER DEFAULT 0,
    "volume_id_version" INTEGER DEFAULT 0,
    "series_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publication_j_version"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "edition" TEXT,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publication_ja_version"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "volume_id" INTEGER NOT NULL,
    "parent" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_id_version" INTEGER DEFAULT 0,
    "parent_version" INTEGER DEFAULT 0,
    "volume_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publication_m_version"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publication_mm_version"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "volume_id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_id_version" INTEGER DEFAULT 0,
    "volume_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publication_mms_version"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "volume_id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_id_version" INTEGER DEFAULT 0,
    "volume_id_version" INTEGER DEFAULT 0,
    "series_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publication_ms_version"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "volumenumberinseries" TEXT,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_id_version" INTEGER DEFAULT 0,
    "series_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publication_publicationgroup_version"
(
    "publicationgroup_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publicationgroup_id_version" INTEGER DEFAULT 0,
    "publication_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publication_version"
(
    "id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    "place_id" INTEGER,
    "publicationdate_id" INTEGER,
    "firstpublicationdate_id" INTEGER,
    "printrun_id" INTEGER,
    "publishingcompany_id" INTEGER,
    "partner_id" INTEGER,
    "editiondescription" TEXT,
    "digitaleditioneditor" TEXT,
    "transcriptioncomment" TEXT,
    "font_id" INTEGER,
    "comment" TEXT,
    "relatedset_id" INTEGER,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "work_id_version" INTEGER DEFAULT 0,
    "publishingcompany_id_version" INTEGER DEFAULT 0,
    "place_id_version" INTEGER DEFAULT 0,
    "printrun_id_version" INTEGER DEFAULT 0,
    "relatedset_id_version" INTEGER DEFAULT 0,
    "publicationdate_id_version" INTEGER DEFAULT 0,
    "firstpublicationdate_id_version" INTEGER DEFAULT 0,
    "font_id_version" INTEGER DEFAULT 0,
    "publication_m_ids" TEXT,
    "publication_m_versions" TEXT,
    "publication_dm_ids" TEXT,
    "publication_dm_versions" TEXT,
    "publication_mm_ids" TEXT,
    "publication_mm_versions" TEXT,
    "publication_ds_ids" TEXT,
    "publication_ds_versions" TEXT,
    "publication_ms_ids" TEXT,
    "publication_ms_versions" TEXT,
    "publication_ja_ids" TEXT,
    "publication_ja_versions" TEXT,
    "publication_mms_ids" TEXT,
    "publication_mms_versions" TEXT,
    "publication_j_ids" TEXT,
    "publication_j_versions" TEXT,
    "publication_publicationgroup_ids" TEXT,
    "publication_publicationgroup_versions" TEXT,
    "person_publication_ids" TEXT,
    "person_publication_versions" TEXT,
    "task_ids" TEXT,
    "task_versions" TEXT,
    "imagesource_ids" TEXT,
    "imagesource_versions" TEXT,
    "textsource_ids" TEXT,
    "textsource_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publicationgroup_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_publicationgroup_ids" TEXT,
    "publication_publicationgroup_versions" TEXT,
    "task_ids" TEXT,
    "task_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "publishingcompany_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "gnd" VARCHAR(255),
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_ids" TEXT,
    "publication_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "relatedset_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_ids" TEXT,
    "publication_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "series_version"
(
    "id" INTEGER NOT NULL,
    "title_id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "title_id_version" INTEGER DEFAULT 0,
    "publication_ds_ids" TEXT,
    "publication_ds_versions" TEXT,
    "publication_ms_ids" TEXT,
    "publication_ms_versions" TEXT,
    "publication_mms_ids" TEXT,
    "publication_mms_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "tag_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "work_tag_ids" TEXT,
    "work_tag_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "task_version"
(
    "id" INTEGER NOT NULL,
    "tasktype_id" INTEGER NOT NULL,
    "done" BOOLEAN,
    "startdate" DATE,
    "enddate" DATE,
    "comments" TEXT,
    "publicationgroup_id" INTEGER,
    "publication_id" INTEGER,
    "responsibleuser_id" INTEGER,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "tasktype_id_version" INTEGER DEFAULT 0,
    "publicationgroup_id_version" INTEGER DEFAULT 0,
    "publication_id_version" INTEGER DEFAULT 0,
    "responsibleuser_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "tasktype_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "tree_left" INTEGER,
    "tree_right" INTEGER,
    "tree_level" INTEGER,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "task_ids" TEXT,
    "task_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "textsource_version"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "partner_id" INTEGER,
    "texturl" TEXT,
    "license_id" INTEGER,
    "attribution" TEXT,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_id_version" INTEGER DEFAULT 0,
    "license_id_version" INTEGER DEFAULT 0,
    "partner_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "title_version"
(
    "id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "titlefragment_ids" TEXT,
    "titlefragment_versions" TEXT,
    "work_ids" TEXT,
    "work_versions" TEXT,
    "series_ids" TEXT,
    "series_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "titlefragment_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "title_id" INTEGER NOT NULL,
    "titlefragmenttype_id" INTEGER NOT NULL,
    "sortable_rank" INTEGER,
    "name_is_reconstructed" BOOLEAN DEFAULT \'f\',
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "title_id_version" INTEGER DEFAULT 0,
    "titlefragmenttype_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "titlefragmenttype_version"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "titlefragment_ids" TEXT,
    "titlefragment_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "volume_version"
(
    "id" INTEGER NOT NULL,
    "volumedescription" INTEGER,
    "volumenumeric" TEXT,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "publication_mm_ids" TEXT,
    "publication_mm_versions" TEXT,
    "publication_ds_ids" TEXT,
    "publication_ds_versions" TEXT,
    "publication_ja_ids" TEXT,
    "publication_ja_versions" TEXT,
    "publication_mms_ids" TEXT,
    "publication_mms_versions" TEXT,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "work_tag_version"
(
    "tag_id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    "id" INTEGER NOT NULL,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "tag_id_version" INTEGER DEFAULT 0,
    "work_id_version" INTEGER DEFAULT 0,
    PRIMARY KEY ("id","version")
);

CREATE TABLE "work_version"
(
    "id" INTEGER NOT NULL,
    "title_id" INTEGER NOT NULL,
    "datespecification_id" INTEGER,
    "doi" TEXT,
    "comments" TEXT,
    "format" TEXT,
    "directoryname" TEXT,
    "version" INTEGER DEFAULT 0 NOT NULL,
    "version_created_at" TIMESTAMP,
    "version_created_by" VARCHAR(100),
    "title_id_version" INTEGER DEFAULT 0,
    "datespecification_id_version" INTEGER DEFAULT 0,
    "publication_ids" TEXT,
    "publication_versions" TEXT,
    "language_work_ids" TEXT,
    "language_work_versions" TEXT,
    "genre_work_ids" TEXT,
    "genre_work_versions" TEXT,
    "work_tag_ids" TEXT,
    "work_tag_versions" TEXT,
    "category_work_ids" TEXT,
    "category_work_versions" TEXT,
    "person_work_ids" TEXT,
    "person_work_versions" TEXT,
    PRIMARY KEY ("id","version")
);

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
',
);
    }

}