
-----------------------------------------------------------------------
-- titlefragmenttype
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "titlefragmenttype" CASCADE;

CREATE TABLE "titlefragmenttype"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- namefragmenttype
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "namefragmenttype" CASCADE;

CREATE TABLE "namefragmenttype"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- personrole
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "personrole" CASCADE;

CREATE TABLE "personrole"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- category
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "category" CASCADE;

CREATE TABLE "category"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "description" TEXT NOT NULL,
    "categorytype_id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- categorytype
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "categorytype" CASCADE;

CREATE TABLE "categorytype"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- tag
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tag" CASCADE;

CREATE TABLE "tag"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- genre
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "genre" CASCADE;

CREATE TABLE "genre"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication" CASCADE;

CREATE TABLE "publication"
(
    "id" serial NOT NULL,
    "title_id" INTEGER NOT NULL,
    "firsteditionpublication_id" INTEGER,
    "place_id" INTEGER,
    "publicationdate_id" INTEGER,
    "creationdate_id" INTEGER,
    "publishingcompany_id" INTEGER,
    "partner_id" INTEGER,
    "editiondescription" TEXT,
    "digitaleditioneditor" TEXT,
    "transcriptioncomment" TEXT,
    "numpages" TEXT,
    "numpagesnumeric" INTEGER,
    "comment" TEXT,
    "doi" TEXT,
    "format" TEXT,
    "directoryname" TEXT,
    "wwwready" INTEGER,
    "legacy_book_id" INTEGER,
    "publishingcompany_id_is_reconstructed" BOOLEAN DEFAULT 'f',
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication"."firsteditionpublication_id" IS 'Publikation, die die Informationen zur Erstauflage enthält';

COMMENT ON COLUMN "publication"."place_id" IS 'Druckort';

COMMENT ON COLUMN "publication"."publicationdate_id" IS 'Erscheinungsjahr';

COMMENT ON COLUMN "publication"."creationdate_id" IS 'Erscheinungsjahr der Erstausgabe';

COMMENT ON COLUMN "publication"."publishingcompany_id" IS 'Verlag';

COMMENT ON COLUMN "publication"."partner_id" IS 'akquiriert über';

COMMENT ON COLUMN "publication"."editiondescription" IS 'Art der Ausgabe';

COMMENT ON COLUMN "publication"."digitaleditioneditor" IS 'Bearbeiter der digitalen Edition';

COMMENT ON COLUMN "publication"."transcriptioncomment" IS 'Bemerkungen zu den Transkriptionsrichtlinien';

COMMENT ON COLUMN "publication"."numpages" IS 'Anzahl Seiten (Umfang)';

COMMENT ON COLUMN "publication"."numpagesnumeric" IS 'Umfang (normiert)';

COMMENT ON COLUMN "publication"."comment" IS 'Anmerkungen';

COMMENT ON COLUMN "publication"."legacy_book_id" IS 'id_book des Datensatzes aus der alten Datenbank, der dem neuen Datensatz zugrundeliegt.';

-----------------------------------------------------------------------
-- publication_m
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_m" CASCADE;

CREATE TABLE "publication_m"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication_dm
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_dm" CASCADE;

CREATE TABLE "publication_dm"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "title_id" INTEGER NOT NULL,
    "pages" TEXT,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication_dm"."title_id" IS 'Titel des übergeordneten Werkes';

COMMENT ON COLUMN "publication_dm"."pages" IS 'Seitenangabe';

-----------------------------------------------------------------------
-- publication_ds
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_ds" CASCADE;

CREATE TABLE "publication_ds"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "pages" TEXT,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication_ds"."pages" IS 'Seitenangabe';

-----------------------------------------------------------------------
-- publication_ms
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_ms" CASCADE;

CREATE TABLE "publication_ms"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "volumenumberinseries" TEXT,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication_ms"."volumenumberinseries" IS 'Nummer des Bandes innerhalb der Reihe';

-----------------------------------------------------------------------
-- publication_ja
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_ja" CASCADE;

CREATE TABLE "publication_ja"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "parent" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication_ja"."parent" IS 'Zeitschrift, in der erschienen';

-----------------------------------------------------------------------
-- publication_mms
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_mms" CASCADE;

CREATE TABLE "publication_mms"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication_j
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_j" CASCADE;

CREATE TABLE "publication_j"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "edition" TEXT,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication_j"."edition" IS 'Ausgabe';

-----------------------------------------------------------------------
-- volume
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "volume" CASCADE;

CREATE TABLE "volume"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "parentpublication_id" INTEGER NOT NULL,
    "volumedescription" INTEGER,
    "volumenumeric" INTEGER,
    "volumestotal" INTEGER,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "volume"."volumedescription" IS 'Bezeichnung des Bandes (alphanumerisch)';

COMMENT ON COLUMN "volume"."volumenumeric" IS 'Bezeichnung des Bandes (numerisch)';

COMMENT ON COLUMN "volume"."volumestotal" IS 'Anzahl Bände (gesamt)';

-----------------------------------------------------------------------
-- series
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "series" CASCADE;

CREATE TABLE "series"
(
    "id" serial NOT NULL,
    "title_id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publishingcompany
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publishingcompany" CASCADE;

CREATE TABLE "publishingcompany"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "gnd" VARCHAR(255),
    PRIMARY KEY ("id"),
    CONSTRAINT "publishingcompany_U_1" UNIQUE ("gnd")
);

-----------------------------------------------------------------------
-- place
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "place" CASCADE;

CREATE TABLE "place"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "gnd" VARCHAR(255),
    PRIMARY KEY ("id"),
    CONSTRAINT "place_U_1" UNIQUE ("gnd")
);

-----------------------------------------------------------------------
-- datespecification
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "datespecification" CASCADE;

CREATE TABLE "datespecification"
(
    "id" serial NOT NULL,
    "year" INTEGER,
    "comments" TEXT,
    "year_is_reconstructed" BOOLEAN DEFAULT 'f',
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- personalname
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "personalname" CASCADE;

CREATE TABLE "personalname"
(
    "id" serial NOT NULL,
    "person_id" INTEGER NOT NULL,
    "sortable_rank" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- namefragment
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "namefragment" CASCADE;

CREATE TABLE "namefragment"
(
    "id" serial NOT NULL,
    "personalname_id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "namefragmenttypeid" INTEGER NOT NULL,
    "sortable_rank" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- title
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "title" CASCADE;

CREATE TABLE "title"
(
    "id" serial NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- titlefragment
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "titlefragment" CASCADE;

CREATE TABLE "titlefragment"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "title_id" INTEGER NOT NULL,
    "titlefragmenttype_id" INTEGER NOT NULL,
    "sortable_rank" INTEGER,
    "name_is_reconstructed" BOOLEAN DEFAULT 'f',
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- person
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "person" CASCADE;

CREATE TABLE "person"
(
    "id" serial NOT NULL,
    "gnd" VARCHAR(255),
    PRIMARY KEY ("id"),
    CONSTRAINT "person_U_1" UNIQUE ("gnd")
);

-----------------------------------------------------------------------
-- font
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "font" CASCADE;

CREATE TABLE "font"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- language
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "language" CASCADE;

CREATE TABLE "language"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- language_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "language_publication" CASCADE;

CREATE TABLE "language_publication"
(
    "language_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "id" serial NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- genre_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "genre_publication" CASCADE;

CREATE TABLE "genre_publication"
(
    "genre_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "id" serial NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication_tag
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_tag" CASCADE;

CREATE TABLE "publication_tag"
(
    "tag_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "id" serial NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- category_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "category_publication" CASCADE;

CREATE TABLE "category_publication"
(
    "category_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "id" serial NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- font_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "font_publication" CASCADE;

CREATE TABLE "font_publication"
(
    "font_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "id" serial NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication_publicationgroup
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_publicationgroup" CASCADE;

CREATE TABLE "publication_publicationgroup"
(
    "publicationgroup_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "id" serial NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- person_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "person_publication" CASCADE;

CREATE TABLE "person_publication"
(
    "person_id" INTEGER NOT NULL,
    "personrole_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "id" serial NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- dta_user
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "dta_user" CASCADE;

CREATE TABLE "dta_user"
(
    "username" TEXT,
    "password" VARCHAR(512),
    "salt" VARCHAR(512),
    "mail" TEXT,
    "admin" BOOLEAN DEFAULT 'f',
    "id" serial NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- task
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "task" CASCADE;

CREATE TABLE "task"
(
    "id" serial NOT NULL,
    "tasktype_id" INTEGER NOT NULL,
    "done" BOOLEAN,
    "start_date" DATE,
    "end_date" DATE,
    "comments" TEXT,
    "publicationgroup_id" INTEGER,
    "publication_id" INTEGER,
    "partner_id" INTEGER,
    "responsibleuser_id" INTEGER,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- tasktype
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tasktype" CASCADE;

CREATE TABLE "tasktype"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "tree_left" INTEGER,
    "tree_right" INTEGER,
    "tree_level" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- partner
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "partner" CASCADE;

CREATE TABLE "partner"
(
    "id" serial NOT NULL,
    "name" TEXT,
    "mail" TEXT,
    "web" TEXT,
    "contactperson" TEXT,
    "contactdata" TEXT,
    "comments" TEXT,
    "is_organization" BOOLEAN DEFAULT 'f',
    "legacy_partner_id" INTEGER,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "partner"."contactperson" IS 'Ansprechpartner';

COMMENT ON COLUMN "partner"."legacy_partner_id" IS 'id_book_location (=Partner/Bibliothek) des Datensatzes aus der alten Datenbank, der dem neuen Datensatz zugrundeliegt.';

-----------------------------------------------------------------------
-- imagesource
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "imagesource" CASCADE;

CREATE TABLE "imagesource"
(
    "id" serial NOT NULL,
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
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "imagesource"."partner_id" IS 'Anbieter Leitdruck';

COMMENT ON COLUMN "imagesource"."catalogueurl" IS 'Link in den Katalog';

COMMENT ON COLUMN "imagesource"."numfaksimiles" IS 'Anzahl Faksimiles';

COMMENT ON COLUMN "imagesource"."extentasofcatalogue" IS 'Umfang laut Katalog';

COMMENT ON COLUMN "imagesource"."faksimilerefrange" IS 'Referenzierte Faksimileseitenzahlen';

COMMENT ON COLUMN "imagesource"."originalrefrange" IS 'Referenzierte Originalseitenzahlen';

COMMENT ON COLUMN "imagesource"."imageurl" IS 'URL der Bilddigitalisate';

COMMENT ON COLUMN "imagesource"."imageurn" IS 'URN der Bilddigitalisate';

COMMENT ON COLUMN "imagesource"."license_id" IS 'Lizenz';

-----------------------------------------------------------------------
-- textsource
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "textsource" CASCADE;

CREATE TABLE "textsource"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "partner_id" INTEGER,
    "texturl" TEXT,
    "license_id" INTEGER,
    "attribution" TEXT,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "textsource"."partner_id" IS 'Anbieter Textdigitalisate';

COMMENT ON COLUMN "textsource"."texturl" IS 'URL der Textdigitalisate';

COMMENT ON COLUMN "textsource"."license_id" IS 'Lizenz';

COMMENT ON COLUMN "textsource"."attribution" IS 'Attributionszeile';

-----------------------------------------------------------------------
-- license
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "license" CASCADE;

CREATE TABLE "license"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "url" TEXT,
    "applicable_to_image" BOOLEAN DEFAULT 'f' NOT NULL,
    "applicable_to_text" BOOLEAN DEFAULT 'f' NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publicationgroup
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publicationgroup" CASCADE;

CREATE TABLE "publicationgroup"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "legacy_group_id" INTEGER,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publicationgroup"."legacy_group_id" IS 'id_group der alten Datenbank, die dem Datensatz zugrundeliegt.';

ALTER TABLE "category" ADD CONSTRAINT "category_FK_1"
    FOREIGN KEY ("categorytype_id")
    REFERENCES "categorytype" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_1"
    FOREIGN KEY ("title_id")
    REFERENCES "title" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_2"
    FOREIGN KEY ("publishingcompany_id")
    REFERENCES "publishingcompany" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_3"
    FOREIGN KEY ("place_id")
    REFERENCES "place" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_4"
    FOREIGN KEY ("publicationdate_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_5"
    FOREIGN KEY ("creationdate_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "publication_m" ADD CONSTRAINT "publication_m_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_dm" ADD CONSTRAINT "publication_dm_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_ds" ADD CONSTRAINT "publication_ds_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_ds" ADD CONSTRAINT "publication_ds_FK_2"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");

ALTER TABLE "publication_ms" ADD CONSTRAINT "publication_ms_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_ms" ADD CONSTRAINT "publication_ms_FK_2"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");

ALTER TABLE "publication_ja" ADD CONSTRAINT "publication_ja_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_mms" ADD CONSTRAINT "publication_mms_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_mms" ADD CONSTRAINT "publication_mms_FK_2"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");

ALTER TABLE "publication_j" ADD CONSTRAINT "publication_j_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "volume" ADD CONSTRAINT "volume_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "volume" ADD CONSTRAINT "volume_FK_2"
    FOREIGN KEY ("parentpublication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "series" ADD CONSTRAINT "series_FK_1"
    FOREIGN KEY ("title_id")
    REFERENCES "title" ("id");

ALTER TABLE "personalname" ADD CONSTRAINT "personalname_FK_1"
    FOREIGN KEY ("person_id")
    REFERENCES "person" ("id")
    ON DELETE CASCADE;

ALTER TABLE "namefragment" ADD CONSTRAINT "namefragment_FK_1"
    FOREIGN KEY ("namefragmenttypeid")
    REFERENCES "namefragmenttype" ("id");

ALTER TABLE "namefragment" ADD CONSTRAINT "namefragment_FK_2"
    FOREIGN KEY ("personalname_id")
    REFERENCES "personalname" ("id")
    ON DELETE CASCADE;

ALTER TABLE "titlefragment" ADD CONSTRAINT "titlefragment_FK_1"
    FOREIGN KEY ("title_id")
    REFERENCES "title" ("id")
    ON DELETE CASCADE;

ALTER TABLE "titlefragment" ADD CONSTRAINT "titlefragment_FK_2"
    FOREIGN KEY ("titlefragmenttype_id")
    REFERENCES "titlefragmenttype" ("id");

ALTER TABLE "language_publication" ADD CONSTRAINT "language_publication_FK_1"
    FOREIGN KEY ("language_id")
    REFERENCES "language" ("id");

ALTER TABLE "language_publication" ADD CONSTRAINT "language_publication_FK_2"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "genre_publication" ADD CONSTRAINT "genre_publication_FK_1"
    FOREIGN KEY ("genre_id")
    REFERENCES "genre" ("id");

ALTER TABLE "genre_publication" ADD CONSTRAINT "genre_publication_FK_2"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_tag" ADD CONSTRAINT "publication_tag_FK_1"
    FOREIGN KEY ("tag_id")
    REFERENCES "tag" ("id");

ALTER TABLE "publication_tag" ADD CONSTRAINT "publication_tag_FK_2"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "category_publication" ADD CONSTRAINT "category_publication_FK_1"
    FOREIGN KEY ("category_id")
    REFERENCES "category" ("id");

ALTER TABLE "category_publication" ADD CONSTRAINT "category_publication_FK_2"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "font_publication" ADD CONSTRAINT "font_publication_FK_1"
    FOREIGN KEY ("font_id")
    REFERENCES "font" ("id");

ALTER TABLE "font_publication" ADD CONSTRAINT "font_publication_FK_2"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_publicationgroup" ADD CONSTRAINT "publication_publicationgrou_FK_1"
    FOREIGN KEY ("publicationgroup_id")
    REFERENCES "publicationgroup" ("id");

ALTER TABLE "publication_publicationgroup" ADD CONSTRAINT "publication_publicationgrou_FK_2"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "person_publication" ADD CONSTRAINT "person_publication_FK_1"
    FOREIGN KEY ("person_id")
    REFERENCES "person" ("id");

ALTER TABLE "person_publication" ADD CONSTRAINT "person_publication_FK_2"
    FOREIGN KEY ("personrole_id")
    REFERENCES "personrole" ("id");

ALTER TABLE "person_publication" ADD CONSTRAINT "person_publication_FK_3"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "task" ADD CONSTRAINT "task_FK_1"
    FOREIGN KEY ("tasktype_id")
    REFERENCES "tasktype" ("id");

ALTER TABLE "task" ADD CONSTRAINT "task_FK_2"
    FOREIGN KEY ("publicationgroup_id")
    REFERENCES "publicationgroup" ("id");

ALTER TABLE "task" ADD CONSTRAINT "task_FK_3"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "task" ADD CONSTRAINT "task_FK_4"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id");

ALTER TABLE "task" ADD CONSTRAINT "task_FK_5"
    FOREIGN KEY ("responsibleuser_id")
    REFERENCES "dta_user" ("id");

ALTER TABLE "imagesource" ADD CONSTRAINT "imagesource_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "imagesource" ADD CONSTRAINT "imagesource_FK_2"
    FOREIGN KEY ("license_id")
    REFERENCES "license" ("id");

ALTER TABLE "imagesource" ADD CONSTRAINT "imagesource_FK_3"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id")
    ON DELETE SET NULL;

ALTER TABLE "textsource" ADD CONSTRAINT "textsource_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "textsource" ADD CONSTRAINT "textsource_FK_2"
    FOREIGN KEY ("license_id")
    REFERENCES "license" ("id");

ALTER TABLE "textsource" ADD CONSTRAINT "textsource_FK_3"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id")
    ON DELETE SET NULL;
