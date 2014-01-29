
-----------------------------------------------------------------------
-- titlefragmenttype
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "titlefragmenttype" CASCADE;

CREATE TABLE "titlefragmenttype"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- source
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "source" CASCADE;

CREATE TABLE "source"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication" CASCADE;

CREATE TABLE "publication"
(
    "id" INTEGER NOT NULL,
    "type" INT2,
    "legacytype" TEXT,
    "title_id" INTEGER NOT NULL,
    "place_id" INTEGER,
    "publicationdate_id" INTEGER,
    "creationdate_id" INTEGER,
    "publishingcompany_id" INTEGER,
    "source_id" INTEGER,
    "legacygenre" TEXT,
    "legacysubgenre" TEXT,
    "legacy_dwds_category1" TEXT,
    "legacy_dwds_subcategory1" TEXT,
    "legacy_dwds_category2" TEXT,
    "legacy_dwds_subcategory2" TEXT,
    "dirname" TEXT,
    "usedcopylocation_id" INTEGER,
    "partner_id" INTEGER,
    "editiondescription" TEXT,
    "digitaleditioneditor" TEXT,
    "citation" TEXT,
    "printrun" TEXT,
    "printrun_numeric" INTEGER,
    "numpages" TEXT,
    "numpagesnumeric" INTEGER,
    "firstpage" INTEGER,
    "comment" TEXT,
    "editioncomment" TEXT,
    "transcriptioncomment" TEXT,
    "encoding_comment" TEXT,
    "firstedition_comment" TEXT,
    "doi" TEXT,
    "format" TEXT,
    "wwwready" INTEGER,
    "last_changed_by_user_id" INTEGER,
    "tree_id" INTEGER,
    "tree_left" INTEGER,
    "tree_right" INTEGER,
    "tree_level" INTEGER,
    "publishingcompany_id_is_reconstructed" BOOLEAN DEFAULT 'f',
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication"."type" IS 'Publikationstyp. Zur Auflösung des dynamischen Typs (ein Volume bettet ein Publication objekt ein, mit nichts als dem Publikationsobjekt in der Hand, lässt sich das zugehörige speziellere objekt aber nur durch ausprobieren aller objektarten herausfinden.)';

COMMENT ON COLUMN "publication"."legacytype" IS 'Altes Publikationstypen-Kürzel (J, JA, M, MM, MMS, etc.)';

COMMENT ON COLUMN "publication"."place_id" IS 'Druckort';

COMMENT ON COLUMN "publication"."publicationdate_id" IS 'Erscheinungsjahr';

COMMENT ON COLUMN "publication"."creationdate_id" IS 'Erscheinungsjahr der Erstausgabe';

COMMENT ON COLUMN "publication"."publishingcompany_id" IS 'Verlag';

COMMENT ON COLUMN "publication"."source_id" IS 'Zur Sicherheit aus der alten DB übernommen';

COMMENT ON COLUMN "publication"."legacygenre" IS 'Alt-Angabe zum Genre, zur Weiterverarbeitung bei Umstellung auf das neue Genre-System.';

COMMENT ON COLUMN "publication"."legacysubgenre" IS 'Alt-Angabe zum Untergenre.';

COMMENT ON COLUMN "publication"."dirname" IS 'Textuelle ID (Kombination aus Autor, Titel, Jahr)';

COMMENT ON COLUMN "publication"."usedcopylocation_id" IS 'Vermutlich der eingesetzte Nachweis. Entspricht dem alten metadaten.id_nachweis. ';

COMMENT ON COLUMN "publication"."partner_id" IS 'akquiriert über';

COMMENT ON COLUMN "publication"."editiondescription" IS 'Art der Ausgabe';

COMMENT ON COLUMN "publication"."digitaleditioneditor" IS 'Bearbeiter der digitalen Edition';

COMMENT ON COLUMN "publication"."citation" IS 'Bibliografische Angabe';

COMMENT ON COLUMN "publication"."printrun" IS 'Auflage';

COMMENT ON COLUMN "publication"."printrun_numeric" IS 'Auflage (numerisch)';

COMMENT ON COLUMN "publication"."numpages" IS 'Anzahl Seiten (Umfang)';

COMMENT ON COLUMN "publication"."numpagesnumeric" IS 'Umfang (normiert)';

COMMENT ON COLUMN "publication"."firstpage" IS 'Startseite';

COMMENT ON COLUMN "publication"."comment" IS 'Anmerkungen';

COMMENT ON COLUMN "publication"."transcriptioncomment" IS 'Bemerkungen zu den Transkriptionsrichtlinien';

COMMENT ON COLUMN "publication"."encoding_comment" IS 'Kommentar Encoding';

COMMENT ON COLUMN "publication"."firstedition_comment" IS 'Kommentar Encoding';

COMMENT ON COLUMN "publication"."tree_id" IS 'Publikationen können vertikal organisiert werden (Teil/Ganzes). Die id dient zur Unterscheidung der einzelnen Bäume.';

CREATE INDEX "publication_I_1" ON "publication" ("tree_id");

CREATE INDEX "publication_I_2" ON "publication" ("type");

-----------------------------------------------------------------------
-- multi_volume
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "multi_volume" CASCADE;

CREATE TABLE "multi_volume"
(
    "id" INTEGER NOT NULL,
    "volumes_total" INTEGER,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id"),
    CONSTRAINT "multi_volume_U_1" UNIQUE ("id")
);

COMMENT ON COLUMN "multi_volume"."volumes_total" IS 'Anzahl Bände (gesamt)';

-----------------------------------------------------------------------
-- volume
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "volume" CASCADE;

CREATE TABLE "volume"
(
    "id" INTEGER NOT NULL,
    "volume_description" TEXT,
    "volume_numeric" INTEGER,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id"),
    CONSTRAINT "volume_U_1" UNIQUE ("id")
);

COMMENT ON COLUMN "volume"."volume_description" IS 'Bezeichnung des Bandes (alphanumerisch)';

COMMENT ON COLUMN "volume"."volume_numeric" IS 'Bezeichnung des Bandes (numerisch)';

-----------------------------------------------------------------------
-- chapter
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "chapter" CASCADE;

CREATE TABLE "chapter"
(
    "id" INTEGER NOT NULL,
    "pages" TEXT,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id"),
    CONSTRAINT "chapter_U_1" UNIQUE ("id")
);

COMMENT ON COLUMN "chapter"."pages" IS 'Seitenangabe';

-----------------------------------------------------------------------
-- article
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "article" CASCADE;

CREATE TABLE "article"
(
    "id" INTEGER NOT NULL,
    "pages" TEXT,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id"),
    CONSTRAINT "article_U_1" UNIQUE ("id")
);

COMMENT ON COLUMN "article"."pages" IS 'Seitenangabe';

-----------------------------------------------------------------------
-- series
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "series" CASCADE;

CREATE TABLE "series"
(
    "id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id"),
    CONSTRAINT "series_U_1" UNIQUE ("id")
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- title
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "title" CASCADE;

CREATE TABLE "title"
(
    "id" serial NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- series_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "series_publication" CASCADE;

CREATE TABLE "series_publication"
(
    "id" serial NOT NULL,
    "series_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "issue" TEXT,
    "volume" TEXT,
    "sortable_rank" INTEGER,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "series_publication"."issue" IS 'Band/Ausgabe';

COMMENT ON COLUMN "series_publication"."volume" IS 'Jahrgang in der Reihe';

-----------------------------------------------------------------------
-- language_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "language_publication" CASCADE;

CREATE TABLE "language_publication"
(
    "id" serial NOT NULL,
    "language_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- genre_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "genre_publication" CASCADE;

CREATE TABLE "genre_publication"
(
    "id" serial NOT NULL,
    "genre_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication_tag
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_tag" CASCADE;

CREATE TABLE "publication_tag"
(
    "id" serial NOT NULL,
    "tag_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- category_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "category_publication" CASCADE;

CREATE TABLE "category_publication"
(
    "id" serial NOT NULL,
    "category_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- font_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "font_publication" CASCADE;

CREATE TABLE "font_publication"
(
    "id" serial NOT NULL,
    "font_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication_publicationgroup
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_publicationgroup" CASCADE;

CREATE TABLE "publication_publicationgroup"
(
    "id" serial NOT NULL,
    "publicationgroup_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- person_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "person_publication" CASCADE;

CREATE TABLE "person_publication"
(
    "id" serial NOT NULL,
    "person_id" INTEGER NOT NULL,
    "personrole_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- recent_use
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "recent_use" CASCADE;

CREATE TABLE "recent_use"
(
    "id" serial NOT NULL,
    "dta_user_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "date" TIMESTAMP NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- dta_user
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "dta_user" CASCADE;

CREATE TABLE "dta_user"
(
    "id" INTEGER NOT NULL,
    "username" TEXT,
    "password" VARCHAR(512),
    "salt" VARCHAR(512),
    "mail" TEXT,
    "admin" BOOLEAN DEFAULT 'f',
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "closed" BOOLEAN,
    "start_date" DATE,
    "end_date" DATE,
    "comments" TEXT,
    "publicationgroup_id" INTEGER,
    "publication_id" INTEGER,
    "partner_id" INTEGER,
    "responsibleuser_id" INTEGER,
    "copylocation_id" INTEGER,
    "priority" INTEGER,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "task"."priority" IS 'Ein hoher Prioritätswert zeigt Dringlichkeit an.';

-----------------------------------------------------------------------
-- tasktype
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tasktype" CASCADE;

CREATE TABLE "tasktype"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "tree_left" INTEGER,
    "tree_right" INTEGER,
    "tree_level" INTEGER,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- copy_location
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "copy_location" CASCADE;

CREATE TABLE "copy_location"
(
    "id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "partner_id" INTEGER,
    "catalogue_signature" TEXT,
    "catalogue_internal" TEXT,
    "catalogue_url" TEXT,
    "numfaksimiles" INTEGER,
    "catalogue_extent" TEXT,
    "available" BOOLEAN,
    "comments" TEXT,
    "imageurl" TEXT,
    "imageurn" TEXT,
    "license_id" INTEGER,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "copy_location"."partner_id" IS 'Anbieter Leitdruck';

COMMENT ON COLUMN "copy_location"."numfaksimiles" IS 'Anzahl Faksimiles';

COMMENT ON COLUMN "copy_location"."catalogue_extent" IS 'Umfang laut Katalog';

COMMENT ON COLUMN "copy_location"."imageurl" IS 'URL der Bilddigitalisate';

COMMENT ON COLUMN "copy_location"."imageurn" IS 'URN der Bilddigitalisate';

COMMENT ON COLUMN "copy_location"."license_id" IS 'Lizenz';

-----------------------------------------------------------------------
-- partner
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "partner" CASCADE;

CREATE TABLE "partner"
(
    "id" INTEGER NOT NULL,
    "name" TEXT,
    "mail" TEXT,
    "web" TEXT,
    "contact_person" TEXT,
    "contactdata" TEXT,
    "comments" TEXT,
    "is_organization" BOOLEAN DEFAULT 'f',
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "partner"."contact_person" IS 'Ansprechpartner';

-----------------------------------------------------------------------
-- imagesource
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "imagesource" CASCADE;

CREATE TABLE "imagesource"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "partner_id" INTEGER,
    "faksimilerefrange" TEXT,
    "originalrefrange" TEXT,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "imagesource"."faksimilerefrange" IS 'Referenzierte Faksimileseitenzahlen';

COMMENT ON COLUMN "imagesource"."originalrefrange" IS 'Referenzierte Originalseitenzahlen';

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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
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
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publicationgroup
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publicationgroup" CASCADE;

CREATE TABLE "publicationgroup"
(
    "id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

ALTER TABLE "category" ADD CONSTRAINT "category_FK_1"
    FOREIGN KEY ("categorytype_id")
    REFERENCES "categorytype" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_1"
    FOREIGN KEY ("title_id")
    REFERENCES "title" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_2"
    FOREIGN KEY ("source_id")
    REFERENCES "source" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_3"
    FOREIGN KEY ("publishingcompany_id")
    REFERENCES "publishingcompany" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_4"
    FOREIGN KEY ("place_id")
    REFERENCES "place" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_5"
    FOREIGN KEY ("publicationdate_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_6"
    FOREIGN KEY ("creationdate_id")
    REFERENCES "datespecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_7"
    FOREIGN KEY ("last_changed_by_user_id")
    REFERENCES "dta_user" ("id");

ALTER TABLE "multi_volume" ADD CONSTRAINT "multi_volume_FK_1"
    FOREIGN KEY ("id")
    REFERENCES "publication" ("id");

ALTER TABLE "volume" ADD CONSTRAINT "volume_FK_1"
    FOREIGN KEY ("id")
    REFERENCES "publication" ("id");

ALTER TABLE "chapter" ADD CONSTRAINT "chapter_FK_1"
    FOREIGN KEY ("id")
    REFERENCES "publication" ("id");

ALTER TABLE "article" ADD CONSTRAINT "article_FK_1"
    FOREIGN KEY ("id")
    REFERENCES "publication" ("id");

ALTER TABLE "series" ADD CONSTRAINT "series_FK_1"
    FOREIGN KEY ("id")
    REFERENCES "publication" ("id");

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

ALTER TABLE "series_publication" ADD CONSTRAINT "series_publication_FK_1"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");

ALTER TABLE "series_publication" ADD CONSTRAINT "series_publication_FK_2"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

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

ALTER TABLE "recent_use" ADD CONSTRAINT "recent_use_FK_1"
    FOREIGN KEY ("dta_user_id")
    REFERENCES "dta_user" ("id");

ALTER TABLE "recent_use" ADD CONSTRAINT "recent_use_FK_2"
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

ALTER TABLE "task" ADD CONSTRAINT "task_FK_6"
    FOREIGN KEY ("copylocation_id")
    REFERENCES "copy_location" ("id");

ALTER TABLE "copy_location" ADD CONSTRAINT "copy_location_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "copy_location" ADD CONSTRAINT "copy_location_FK_2"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id");

ALTER TABLE "copy_location" ADD CONSTRAINT "copy_location_FK_3"
    FOREIGN KEY ("license_id")
    REFERENCES "license" ("id");

ALTER TABLE "imagesource" ADD CONSTRAINT "imagesource_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "imagesource" ADD CONSTRAINT "imagesource_FK_2"
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
