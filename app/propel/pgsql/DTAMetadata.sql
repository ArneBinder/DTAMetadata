
-----------------------------------------------------------------------
-- titleFragmentType
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "titleFragmentType" CASCADE;

CREATE TABLE "titleFragmentType"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- nameFragmentType
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "nameFragmentType" CASCADE;

CREATE TABLE "nameFragmentType"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- personRole
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "personRole" CASCADE;

CREATE TABLE "personRole"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "applicable_to_publication" BOOLEAN DEFAULT 'f' NOT NULL,
    "applicable_to_work" BOOLEAN DEFAULT 'f' NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- corpus
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "corpus" CASCADE;

CREATE TABLE "corpus"
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
    "childOf" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication" CASCADE;

CREATE TABLE "publication"
(
    "id" serial NOT NULL,
    "work_id" INTEGER NOT NULL,
    "title_id" INTEGER NOT NULL,
    "place_id" INTEGER,
    "publicationDate_id" INTEGER,
    "firstPublicationDate_id" INTEGER,
    "printRun_id" INTEGER NOT NULL,
    "publishingCompany_id" INTEGER,
    "editionDescription" TEXT,
    "digitalEditionEditor" TEXT,
    "transcriptionComment" TEXT,
    "font_id" INTEGER,
    "comment" TEXT,
    "relatedSet_id" INTEGER,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication"."place_id" IS 'Druckort';

COMMENT ON COLUMN "publication"."publicationDate_id" IS 'Erscheinungsjahr';

COMMENT ON COLUMN "publication"."firstPublicationDate_id" IS 'Erscheinungsjahr der Erstausgabe';

COMMENT ON COLUMN "publication"."printRun_id" IS 'Informationen zur Auflage';

COMMENT ON COLUMN "publication"."publishingCompany_id" IS 'Verlag';

COMMENT ON COLUMN "publication"."editionDescription" IS 'Art der Ausgabe';

COMMENT ON COLUMN "publication"."digitalEditionEditor" IS 'Bearbeiter der digitalen Edition';

COMMENT ON COLUMN "publication"."transcriptionComment" IS 'Bemerkungen zu den Transkriptionsrichtlinien';

COMMENT ON COLUMN "publication"."font_id" IS 'Vorherrschende Schriftart';

COMMENT ON COLUMN "publication"."comment" IS 'Anmerkungen';

-----------------------------------------------------------------------
-- work
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "work" CASCADE;

CREATE TABLE "work"
(
    "id" serial NOT NULL,
    "dateSpecification_id" INTEGER,
    "doi" TEXT,
    "comments" TEXT,
    "format" TEXT,
    "directoryName" TEXT,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- printRun
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "printRun" CASCADE;

CREATE TABLE "printRun"
(
    "id" serial NOT NULL,
    "name" TEXT,
    "numeric" INTEGER,
    "numPages" INTEGER,
    "numPagesNormed" INTEGER,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "printRun"."name" IS 'Bezeichnung';

COMMENT ON COLUMN "printRun"."numeric" IS 'Numerische Bezeichnung der Ausgabe';

-----------------------------------------------------------------------
-- publication_M
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_M" CASCADE;

CREATE TABLE "publication_M"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication_DM
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_DM" CASCADE;

CREATE TABLE "publication_DM"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "parent" INTEGER,
    "pages" TEXT,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication_DM"."parent" IS 'Übergeordnetes Werk';

COMMENT ON COLUMN "publication_DM"."pages" IS 'Seitenangabe';

-----------------------------------------------------------------------
-- publication_MM
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_MM" CASCADE;

CREATE TABLE "publication_MM"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "volume_id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication_DS
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_DS" CASCADE;

CREATE TABLE "publication_DS"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "volume_id" INTEGER NOT NULL,
    "pages" TEXT,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication_DS"."pages" IS 'Seitenangabe';

-----------------------------------------------------------------------
-- publication_MS
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_MS" CASCADE;

CREATE TABLE "publication_MS"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "volumeNumberInSeries" TEXT,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication_MS"."volumeNumberInSeries" IS 'Nummer des Bandes innerhalb der Reihe';

-----------------------------------------------------------------------
-- publication_JA
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_JA" CASCADE;

CREATE TABLE "publication_JA"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "volume_id" INTEGER NOT NULL,
    "parent" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication_JA"."volume_id" IS 'Teil (bei einer mehrteiligen Publikation)';

COMMENT ON COLUMN "publication_JA"."parent" IS 'Zeitschrift, in der erschienen';

-----------------------------------------------------------------------
-- publication_MMS
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_MMS" CASCADE;

CREATE TABLE "publication_MMS"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "series_id" INTEGER NOT NULL,
    "volume_id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- publication_J
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_J" CASCADE;

CREATE TABLE "publication_J"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "edition" TEXT,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "publication_J"."edition" IS 'Ausgabe';

-----------------------------------------------------------------------
-- volume
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "volume" CASCADE;

CREATE TABLE "volume"
(
    "id" serial NOT NULL,
    "volumeDescription" INTEGER,
    "volumeNumeric" TEXT,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "volume"."volumeDescription" IS 'Bezeichnung des Bandes';

COMMENT ON COLUMN "volume"."volumeNumeric" IS 'Numerische Bezeichnung des Bandes';

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
-- publishingCompany
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publishingCompany" CASCADE;

CREATE TABLE "publishingCompany"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "gnd" VARCHAR(255),
    PRIMARY KEY ("id"),
    CONSTRAINT "publishingCompany_U_1" UNIQUE ("gnd")
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
-- dateSpecification
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "dateSpecification" CASCADE;

CREATE TABLE "dateSpecification"
(
    "id" serial NOT NULL,
    "year" INTEGER,
    "comments" TEXT,
    "year_is_reconstructed" BOOLEAN DEFAULT 'f',
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- personalName
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "personalName" CASCADE;

CREATE TABLE "personalName"
(
    "id" serial NOT NULL,
    "person_id" INTEGER NOT NULL,
    "sortable_rank" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- nameFragment
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "nameFragment" CASCADE;

CREATE TABLE "nameFragment"
(
    "id" serial NOT NULL,
    "personalName_id" INTEGER NOT NULL,
    "name" TEXT NOT NULL,
    "nameFragmentTypeId" INTEGER NOT NULL,
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
-- titleFragment
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "titleFragment" CASCADE;

CREATE TABLE "titleFragment"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    "title_id" INTEGER NOT NULL,
    "titleFragmentType_id" INTEGER NOT NULL,
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
-- language_work
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "language_work" CASCADE;

CREATE TABLE "language_work"
(
    "language_id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    PRIMARY KEY ("language_id","work_id")
);

-----------------------------------------------------------------------
-- genre_work
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "genre_work" CASCADE;

CREATE TABLE "genre_work"
(
    "genre_id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    PRIMARY KEY ("genre_id","work_id")
);

-----------------------------------------------------------------------
-- work_tag
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "work_tag" CASCADE;

CREATE TABLE "work_tag"
(
    "tag_id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    PRIMARY KEY ("tag_id","work_id")
);

-----------------------------------------------------------------------
-- category_work
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "category_work" CASCADE;

CREATE TABLE "category_work"
(
    "category_id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    PRIMARY KEY ("category_id","work_id")
);

-----------------------------------------------------------------------
-- publication_publicationGroup
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publication_publicationGroup" CASCADE;

CREATE TABLE "publication_publicationGroup"
(
    "publicationGroup_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    PRIMARY KEY ("publicationGroup_id","publication_id")
);

-----------------------------------------------------------------------
-- person_publication
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "person_publication" CASCADE;

CREATE TABLE "person_publication"
(
    "id" serial NOT NULL,
    "personRole_id" INTEGER NOT NULL,
    "person_id" INTEGER NOT NULL,
    "publication_id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- person_work
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "person_work" CASCADE;

CREATE TABLE "person_work"
(
    "id" serial NOT NULL,
    "person_id" INTEGER NOT NULL,
    "personRole_id" INTEGER NOT NULL,
    "work_id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- user
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "user" CASCADE;

CREATE TABLE "user"
(
    "id" serial NOT NULL,
    "username" TEXT,
    "password" VARCHAR(512),
    "salt" VARCHAR(512),
    "mail" TEXT,
    "phone" TEXT,
    "admin" BOOLEAN DEFAULT 'f',
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- relatedSet
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "relatedSet" CASCADE;

CREATE TABLE "relatedSet"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- task
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "task" CASCADE;

CREATE TABLE "task"
(
    "id" serial NOT NULL,
    "taskType_id" INTEGER NOT NULL,
    "done" BOOLEAN,
    "start" DATE,
    "end" DATE,
    "comments" TEXT,
    "publicationGroup_id" INTEGER,
    "publication_id" INTEGER,
    "responsibleUser_id" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- taskType
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "taskType" CASCADE;

CREATE TABLE "taskType"
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
    "address" TEXT,
    "person" TEXT,
    "mail" TEXT,
    "web" TEXT,
    "comments" TEXT,
    "phone1" TEXT,
    "phone2" TEXT,
    "phone3" TEXT,
    "fax" TEXT,
    "is_organization" BOOLEAN DEFAULT 'f',
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- imageSource
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "imageSource" CASCADE;

CREATE TABLE "imageSource"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "partner_id" INTEGER,
    "catalogueSignature" TEXT,
    "catalogueUrl" TEXT,
    "numFaksimiles" INTEGER,
    "extentAsOfCatalogue" TEXT,
    "numPages" INTEGER,
    "imageUrl" TEXT,
    "imageUrn" TEXT,
    "license_id" INTEGER,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "imageSource"."partner_id" IS 'Anbieter Leitdruck';

COMMENT ON COLUMN "imageSource"."catalogueUrl" IS 'Link in den Katalog';

COMMENT ON COLUMN "imageSource"."numFaksimiles" IS 'Anzahl Faksimiles';

COMMENT ON COLUMN "imageSource"."extentAsOfCatalogue" IS 'Umfang laut Katalog';

COMMENT ON COLUMN "imageSource"."numPages" IS 'Umfang lt. Katalog';

COMMENT ON COLUMN "imageSource"."imageUrl" IS 'URL der Bilddigitalisate';

COMMENT ON COLUMN "imageSource"."imageUrn" IS 'URN der Bilddigitalisate';

COMMENT ON COLUMN "imageSource"."license_id" IS 'Lizenz';

-----------------------------------------------------------------------
-- textSource
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "textSource" CASCADE;

CREATE TABLE "textSource"
(
    "id" serial NOT NULL,
    "publication_id" INTEGER NOT NULL,
    "partner_id" INTEGER,
    "imageUrl" TEXT,
    "license_id" INTEGER,
    PRIMARY KEY ("id")
);

COMMENT ON COLUMN "textSource"."partner_id" IS 'Anbieter Textdigitalisate';

COMMENT ON COLUMN "textSource"."imageUrl" IS 'URL der Textdigitalisate';

COMMENT ON COLUMN "textSource"."license_id" IS 'Lizenz';

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
-- publicationGroup
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "publicationGroup" CASCADE;

CREATE TABLE "publicationGroup"
(
    "id" serial NOT NULL,
    "name" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

ALTER TABLE "genre" ADD CONSTRAINT "genre_FK_1"
    FOREIGN KEY ("childOf")
    REFERENCES "genre" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_1"
    FOREIGN KEY ("work_id")
    REFERENCES "work" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_2"
    FOREIGN KEY ("title_id")
    REFERENCES "title" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_3"
    FOREIGN KEY ("publishingCompany_id")
    REFERENCES "publishingCompany" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_4"
    FOREIGN KEY ("place_id")
    REFERENCES "place" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_5"
    FOREIGN KEY ("printRun_id")
    REFERENCES "printRun" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_6"
    FOREIGN KEY ("relatedSet_id")
    REFERENCES "relatedSet" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_7"
    FOREIGN KEY ("publicationDate_id")
    REFERENCES "dateSpecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_8"
    FOREIGN KEY ("firstPublicationDate_id")
    REFERENCES "dateSpecification" ("id");

ALTER TABLE "publication" ADD CONSTRAINT "publication_FK_9"
    FOREIGN KEY ("font_id")
    REFERENCES "font" ("id");

ALTER TABLE "work" ADD CONSTRAINT "work_FK_1"
    FOREIGN KEY ("dateSpecification_id")
    REFERENCES "dateSpecification" ("id");

ALTER TABLE "publication_M" ADD CONSTRAINT "publication_M_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_DM" ADD CONSTRAINT "publication_DM_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_DM" ADD CONSTRAINT "publication_DM_FK_2"
    FOREIGN KEY ("parent")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_MM" ADD CONSTRAINT "publication_MM_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_MM" ADD CONSTRAINT "publication_MM_FK_2"
    FOREIGN KEY ("volume_id")
    REFERENCES "volume" ("id");

ALTER TABLE "publication_DS" ADD CONSTRAINT "publication_DS_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_DS" ADD CONSTRAINT "publication_DS_FK_2"
    FOREIGN KEY ("volume_id")
    REFERENCES "volume" ("id");

ALTER TABLE "publication_DS" ADD CONSTRAINT "publication_DS_FK_3"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");

ALTER TABLE "publication_MS" ADD CONSTRAINT "publication_MS_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_MS" ADD CONSTRAINT "publication_MS_FK_2"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");

ALTER TABLE "publication_JA" ADD CONSTRAINT "publication_JA_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_JA" ADD CONSTRAINT "publication_JA_FK_2"
    FOREIGN KEY ("parent")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_JA" ADD CONSTRAINT "publication_JA_FK_3"
    FOREIGN KEY ("volume_id")
    REFERENCES "volume" ("id");

ALTER TABLE "publication_MMS" ADD CONSTRAINT "publication_MMS_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "publication_MMS" ADD CONSTRAINT "publication_MMS_FK_2"
    FOREIGN KEY ("volume_id")
    REFERENCES "volume" ("id");

ALTER TABLE "publication_MMS" ADD CONSTRAINT "publication_MMS_FK_3"
    FOREIGN KEY ("series_id")
    REFERENCES "series" ("id");

ALTER TABLE "publication_J" ADD CONSTRAINT "publication_J_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "series" ADD CONSTRAINT "series_FK_1"
    FOREIGN KEY ("title_id")
    REFERENCES "title" ("id");

ALTER TABLE "personalName" ADD CONSTRAINT "personalName_FK_1"
    FOREIGN KEY ("person_id")
    REFERENCES "person" ("id");

ALTER TABLE "nameFragment" ADD CONSTRAINT "nameFragment_FK_1"
    FOREIGN KEY ("nameFragmentTypeId")
    REFERENCES "nameFragmentType" ("id");

ALTER TABLE "nameFragment" ADD CONSTRAINT "nameFragment_FK_2"
    FOREIGN KEY ("personalName_id")
    REFERENCES "personalName" ("id");

ALTER TABLE "titleFragment" ADD CONSTRAINT "titleFragment_FK_1"
    FOREIGN KEY ("title_id")
    REFERENCES "title" ("id");

ALTER TABLE "titleFragment" ADD CONSTRAINT "titleFragment_FK_2"
    FOREIGN KEY ("titleFragmentType_id")
    REFERENCES "titleFragmentType" ("id");

ALTER TABLE "language_work" ADD CONSTRAINT "language_work_FK_1"
    FOREIGN KEY ("language_id")
    REFERENCES "language" ("id");

ALTER TABLE "language_work" ADD CONSTRAINT "language_work_FK_2"
    FOREIGN KEY ("work_id")
    REFERENCES "work" ("id");

ALTER TABLE "genre_work" ADD CONSTRAINT "genre_work_FK_1"
    FOREIGN KEY ("genre_id")
    REFERENCES "genre" ("id");

ALTER TABLE "genre_work" ADD CONSTRAINT "genre_work_FK_2"
    FOREIGN KEY ("work_id")
    REFERENCES "work" ("id");

ALTER TABLE "work_tag" ADD CONSTRAINT "work_tag_FK_1"
    FOREIGN KEY ("tag_id")
    REFERENCES "tag" ("id");

ALTER TABLE "work_tag" ADD CONSTRAINT "work_tag_FK_2"
    FOREIGN KEY ("work_id")
    REFERENCES "work" ("id");

ALTER TABLE "category_work" ADD CONSTRAINT "category_work_FK_1"
    FOREIGN KEY ("category_id")
    REFERENCES "category" ("id");

ALTER TABLE "category_work" ADD CONSTRAINT "category_work_FK_2"
    FOREIGN KEY ("work_id")
    REFERENCES "work" ("id");

ALTER TABLE "publication_publicationGroup" ADD CONSTRAINT "publication_publicationGrou_FK_1"
    FOREIGN KEY ("publicationGroup_id")
    REFERENCES "publicationGroup" ("id");

ALTER TABLE "publication_publicationGroup" ADD CONSTRAINT "publication_publicationGrou_FK_2"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "person_publication" ADD CONSTRAINT "person_publication_FK_1"
    FOREIGN KEY ("personRole_id")
    REFERENCES "personRole" ("id");

ALTER TABLE "person_publication" ADD CONSTRAINT "person_publication_FK_2"
    FOREIGN KEY ("person_id")
    REFERENCES "person" ("id");

ALTER TABLE "person_publication" ADD CONSTRAINT "person_publication_FK_3"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "person_work" ADD CONSTRAINT "person_work_FK_1"
    FOREIGN KEY ("person_id")
    REFERENCES "person" ("id");

ALTER TABLE "person_work" ADD CONSTRAINT "person_work_FK_2"
    FOREIGN KEY ("personRole_id")
    REFERENCES "personRole" ("id");

ALTER TABLE "person_work" ADD CONSTRAINT "person_work_FK_3"
    FOREIGN KEY ("work_id")
    REFERENCES "work" ("id");

ALTER TABLE "task" ADD CONSTRAINT "task_FK_1"
    FOREIGN KEY ("taskType_id")
    REFERENCES "taskType" ("id");

ALTER TABLE "task" ADD CONSTRAINT "task_FK_2"
    FOREIGN KEY ("publicationGroup_id")
    REFERENCES "publicationGroup" ("id");

ALTER TABLE "task" ADD CONSTRAINT "task_FK_3"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "task" ADD CONSTRAINT "task_FK_4"
    FOREIGN KEY ("responsibleUser_id")
    REFERENCES "user" ("id");

ALTER TABLE "imageSource" ADD CONSTRAINT "imageSource_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "imageSource" ADD CONSTRAINT "imageSource_FK_2"
    FOREIGN KEY ("license_id")
    REFERENCES "license" ("id");

ALTER TABLE "imageSource" ADD CONSTRAINT "imageSource_FK_3"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id");

ALTER TABLE "textSource" ADD CONSTRAINT "textSource_FK_1"
    FOREIGN KEY ("publication_id")
    REFERENCES "publication" ("id");

ALTER TABLE "textSource" ADD CONSTRAINT "textSource_FK_2"
    FOREIGN KEY ("license_id")
    REFERENCES "license" ("id");

ALTER TABLE "textSource" ADD CONSTRAINT "textSource_FK_3"
    FOREIGN KEY ("partner_id")
    REFERENCES "partner" ("id");
