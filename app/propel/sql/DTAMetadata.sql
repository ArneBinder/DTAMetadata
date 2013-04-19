
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- titleFragmentType
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `titleFragmentType`;

CREATE TABLE `titleFragmentType`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- nameFragmentType
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `nameFragmentType`;

CREATE TABLE `nameFragmentType`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- personRole
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `personRole`;

CREATE TABLE `personRole`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `applicable_to_publication` TINYINT(1) DEFAULT 0 NOT NULL,
    `applicable_to_work` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- corpus
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `corpus`;

CREATE TABLE `corpus`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- tag
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `tag`;

CREATE TABLE `tag`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- genre
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `genre`;

CREATE TABLE `genre`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `childOf` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `genre_FI_1` (`childOf`),
    CONSTRAINT `genre_FK_1`
        FOREIGN KEY (`childOf`)
        REFERENCES `genre` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publication
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication`;

CREATE TABLE `publication`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `work_id` INTEGER NOT NULL,
    `title_id` INTEGER NOT NULL,
    `place_id` INTEGER COMMENT 'Druckort',
    `publicationDate_id` INTEGER COMMENT 'Erscheinungsjahr',
    `firstPublicationDate_id` INTEGER COMMENT 'Erscheinungsjahr der Erstausgabe',
    `printRun_id` INTEGER NOT NULL COMMENT 'Informationen zur Auflage',
    `publishingCompany_id` INTEGER COMMENT 'Verlag',
    `editionDescription` TEXT COMMENT 'Art der Ausgabe',
    `digitalEditionEditor` TEXT COMMENT 'Bearbeiter der digitalen Edition',
    `transcriptionComment` TEXT COMMENT 'Bemerkungen zu den Transkriptionsrichtlinien',
    `font_id` INTEGER COMMENT 'Vorherrschende Schriftart',
    `comment` TEXT COMMENT 'Anmerkungen',
    `relatedSet_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `publication_FI_1` (`work_id`),
    INDEX `publication_FI_2` (`title_id`),
    INDEX `publication_FI_3` (`publishingCompany_id`),
    INDEX `publication_FI_4` (`place_id`),
    INDEX `publication_FI_5` (`printRun_id`),
    INDEX `publication_FI_6` (`relatedSet_id`),
    INDEX `publication_FI_7` (`publicationDate_id`),
    INDEX `publication_FI_8` (`firstPublicationDate_id`),
    INDEX `publication_FI_9` (`font_id`),
    CONSTRAINT `publication_FK_1`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`),
    CONSTRAINT `publication_FK_2`
        FOREIGN KEY (`title_id`)
        REFERENCES `title` (`id`),
    CONSTRAINT `publication_FK_3`
        FOREIGN KEY (`publishingCompany_id`)
        REFERENCES `publishingCompany` (`id`),
    CONSTRAINT `publication_FK_4`
        FOREIGN KEY (`place_id`)
        REFERENCES `place` (`id`),
    CONSTRAINT `publication_FK_5`
        FOREIGN KEY (`printRun_id`)
        REFERENCES `printRun` (`id`),
    CONSTRAINT `publication_FK_6`
        FOREIGN KEY (`relatedSet_id`)
        REFERENCES `relatedSet` (`id`),
    CONSTRAINT `publication_FK_7`
        FOREIGN KEY (`publicationDate_id`)
        REFERENCES `dateSpecification` (`id`),
    CONSTRAINT `publication_FK_8`
        FOREIGN KEY (`firstPublicationDate_id`)
        REFERENCES `dateSpecification` (`id`),
    CONSTRAINT `publication_FK_9`
        FOREIGN KEY (`font_id`)
        REFERENCES `font` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- work
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `work`;

CREATE TABLE `work`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `dateSpecification_id` INTEGER,
    `doi` TEXT,
    `comments` TEXT,
    `format` TEXT,
    `directoryName` TEXT,
    PRIMARY KEY (`id`),
    INDEX `work_FI_1` (`dateSpecification_id`),
    CONSTRAINT `work_FK_1`
        FOREIGN KEY (`dateSpecification_id`)
        REFERENCES `dateSpecification` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- printRun
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `printRun`;

CREATE TABLE `printRun`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT COMMENT 'Bezeichnung',
    `numeric` INTEGER COMMENT 'Numerische Bezeichnung der Ausgabe',
    `numPages` INTEGER,
    `numPagesNormed` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publication_M
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication_M`;

CREATE TABLE `publication_M`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `publication_M_FI_1` (`publication_id`),
    CONSTRAINT `publication_M_FK_1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publication_DM
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication_DM`;

CREATE TABLE `publication_DM`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `parent` INTEGER COMMENT 'Übergeordnetes Werk',
    `pages` TEXT COMMENT 'Seitenangabe',
    PRIMARY KEY (`id`),
    INDEX `publication_DM_FI_1` (`publication_id`),
    INDEX `publication_DM_FI_2` (`parent`),
    CONSTRAINT `publication_DM_FK_1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `publication_DM_FK_2`
        FOREIGN KEY (`parent`)
        REFERENCES `publication` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publication_MM
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication_MM`;

CREATE TABLE `publication_MM`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `volume_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `publication_MM_FI_1` (`publication_id`),
    INDEX `publication_MM_FI_2` (`volume_id`),
    CONSTRAINT `publication_MM_FK_1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `publication_MM_FK_2`
        FOREIGN KEY (`volume_id`)
        REFERENCES `volume` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publication_DS
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication_DS`;

CREATE TABLE `publication_DS`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `series_id` INTEGER NOT NULL,
    `volume_id` INTEGER NOT NULL,
    `pages` TEXT COMMENT 'Seitenangabe',
    PRIMARY KEY (`id`),
    INDEX `publication_DS_FI_1` (`publication_id`),
    INDEX `publication_DS_FI_2` (`volume_id`),
    INDEX `publication_DS_FI_3` (`series_id`),
    CONSTRAINT `publication_DS_FK_1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `publication_DS_FK_2`
        FOREIGN KEY (`volume_id`)
        REFERENCES `volume` (`id`),
    CONSTRAINT `publication_DS_FK_3`
        FOREIGN KEY (`series_id`)
        REFERENCES `series` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publication_MS
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication_MS`;

CREATE TABLE `publication_MS`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `series_id` INTEGER NOT NULL,
    `volumeNumberInSeries` TEXT COMMENT 'Nummer des Bandes innerhalb der Reihe',
    PRIMARY KEY (`id`),
    INDEX `publication_MS_FI_1` (`publication_id`),
    INDEX `publication_MS_FI_2` (`series_id`),
    CONSTRAINT `publication_MS_FK_1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `publication_MS_FK_2`
        FOREIGN KEY (`series_id`)
        REFERENCES `series` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publication_JA
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication_JA`;

CREATE TABLE `publication_JA`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `volume_id` INTEGER NOT NULL COMMENT 'Teil (bei einer mehrteiligen Publikation)',
    `parent` INTEGER NOT NULL COMMENT 'Zeitschrift, in der erschienen',
    PRIMARY KEY (`id`),
    INDEX `publication_JA_FI_1` (`publication_id`),
    INDEX `publication_JA_FI_2` (`parent`),
    INDEX `publication_JA_FI_3` (`volume_id`),
    CONSTRAINT `publication_JA_FK_1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `publication_JA_FK_2`
        FOREIGN KEY (`parent`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `publication_JA_FK_3`
        FOREIGN KEY (`volume_id`)
        REFERENCES `volume` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publication_MMS
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication_MMS`;

CREATE TABLE `publication_MMS`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `series_id` INTEGER NOT NULL,
    `volume_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `publication_MMS_FI_1` (`publication_id`),
    INDEX `publication_MMS_FI_2` (`volume_id`),
    INDEX `publication_MMS_FI_3` (`series_id`),
    CONSTRAINT `publication_MMS_FK_1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `publication_MMS_FK_2`
        FOREIGN KEY (`volume_id`)
        REFERENCES `volume` (`id`),
    CONSTRAINT `publication_MMS_FK_3`
        FOREIGN KEY (`series_id`)
        REFERENCES `series` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publication_J
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication_J`;

CREATE TABLE `publication_J`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `edition` TEXT COMMENT 'Ausgabe',
    PRIMARY KEY (`id`),
    INDEX `publication_J_FI_1` (`publication_id`),
    CONSTRAINT `publication_J_FK_1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- volume
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `volume`;

CREATE TABLE `volume`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `volumeDescription` INTEGER COMMENT 'Bezeichnung des Bandes',
    `volumeNumeric` TEXT COMMENT 'Numerische Bezeichnung des Bandes',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- series
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `series`;

CREATE TABLE `series`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `series_FI_1` (`title_id`),
    CONSTRAINT `series_FK_1`
        FOREIGN KEY (`title_id`)
        REFERENCES `title` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publishingCompany
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publishingCompany`;

CREATE TABLE `publishingCompany`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `gnd` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `publishingCompany_U_1` (`gnd`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- place
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `place`;

CREATE TABLE `place`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `gnd` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `place_U_1` (`gnd`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- dateSpecification
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `dateSpecification`;

CREATE TABLE `dateSpecification`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `year` INTEGER,
    `comments` TEXT,
    `year_is_reconstructed` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- personalName
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `personalName`;

CREATE TABLE `personalName`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `personalName_FI_1` (`person_id`),
    CONSTRAINT `personalName_FK_1`
        FOREIGN KEY (`person_id`)
        REFERENCES `person` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- nameFragment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `nameFragment`;

CREATE TABLE `nameFragment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `personalName_id` INTEGER NOT NULL,
    `name` TEXT NOT NULL,
    `nameFragmentTypeId` INTEGER NOT NULL,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `nameFragment_FI_1` (`nameFragmentTypeId`),
    INDEX `nameFragment_FI_2` (`personalName_id`),
    CONSTRAINT `nameFragment_FK_1`
        FOREIGN KEY (`nameFragmentTypeId`)
        REFERENCES `nameFragmentType` (`id`),
    CONSTRAINT `nameFragment_FK_2`
        FOREIGN KEY (`personalName_id`)
        REFERENCES `personalName` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- title
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `title`;

CREATE TABLE `title`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- titleFragment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `titleFragment`;

CREATE TABLE `titleFragment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `title_id` INTEGER NOT NULL,
    `titleFragmentType_id` INTEGER NOT NULL,
    `sortable_rank` INTEGER,
    `name_is_reconstructed` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `titleFragment_FI_1` (`title_id`),
    INDEX `titleFragment_FI_2` (`titleFragmentType_id`),
    CONSTRAINT `titleFragment_FK_1`
        FOREIGN KEY (`title_id`)
        REFERENCES `title` (`id`),
    CONSTRAINT `titleFragment_FK_2`
        FOREIGN KEY (`titleFragmentType_id`)
        REFERENCES `titleFragmentType` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- person
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `person`;

CREATE TABLE `person`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `gnd` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `person_U_1` (`gnd`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- font
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `font`;

CREATE TABLE `font`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- language
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `language`;

CREATE TABLE `language`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- language_work
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `language_work`;

CREATE TABLE `language_work`
(
    `language_id` INTEGER NOT NULL,
    `work_id` INTEGER NOT NULL,
    PRIMARY KEY (`language_id`,`work_id`),
    INDEX `language_work_FI_2` (`work_id`),
    CONSTRAINT `language_work_FK_1`
        FOREIGN KEY (`language_id`)
        REFERENCES `language` (`id`),
    CONSTRAINT `language_work_FK_2`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- genre_work
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `genre_work`;

CREATE TABLE `genre_work`
(
    `genre_id` INTEGER NOT NULL,
    `work_id` INTEGER NOT NULL,
    PRIMARY KEY (`genre_id`,`work_id`),
    INDEX `genre_work_FI_2` (`work_id`),
    CONSTRAINT `genre_work_FK_1`
        FOREIGN KEY (`genre_id`)
        REFERENCES `genre` (`id`),
    CONSTRAINT `genre_work_FK_2`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- work_tag
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `work_tag`;

CREATE TABLE `work_tag`
(
    `tag_id` INTEGER NOT NULL,
    `work_id` INTEGER NOT NULL,
    PRIMARY KEY (`tag_id`,`work_id`),
    INDEX `work_tag_FI_2` (`work_id`),
    CONSTRAINT `work_tag_FK_1`
        FOREIGN KEY (`tag_id`)
        REFERENCES `tag` (`id`),
    CONSTRAINT `work_tag_FK_2`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- category_work
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category_work`;

CREATE TABLE `category_work`
(
    `category_id` INTEGER NOT NULL,
    `work_id` INTEGER NOT NULL,
    PRIMARY KEY (`category_id`,`work_id`),
    INDEX `category_work_FI_2` (`work_id`),
    CONSTRAINT `category_work_FK_1`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`),
    CONSTRAINT `category_work_FK_2`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publication_publicationGroup
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication_publicationGroup`;

CREATE TABLE `publication_publicationGroup`
(
    `publicationGroup_id` INTEGER NOT NULL,
    `publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`publicationGroup_id`,`publication_id`),
    INDEX `publication_publicationGroup_FI_2` (`publication_id`),
    CONSTRAINT `publication_publicationGroup_FK_1`
        FOREIGN KEY (`publicationGroup_id`)
        REFERENCES `publicationGroup` (`id`),
    CONSTRAINT `publication_publicationGroup_FK_2`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- person_publication
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `person_publication`;

CREATE TABLE `person_publication`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `personRole_id` INTEGER NOT NULL,
    `person_id` INTEGER NOT NULL,
    `publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `person_publication_FI_1` (`personRole_id`),
    INDEX `person_publication_FI_2` (`person_id`),
    INDEX `person_publication_FI_3` (`publication_id`),
    CONSTRAINT `person_publication_FK_1`
        FOREIGN KEY (`personRole_id`)
        REFERENCES `personRole` (`id`),
    CONSTRAINT `person_publication_FK_2`
        FOREIGN KEY (`person_id`)
        REFERENCES `person` (`id`),
    CONSTRAINT `person_publication_FK_3`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- person_work
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `person_work`;

CREATE TABLE `person_work`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    `personRole_id` INTEGER NOT NULL,
    `work_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `person_work_FI_1` (`person_id`),
    INDEX `person_work_FI_2` (`personRole_id`),
    INDEX `person_work_FI_3` (`work_id`),
    CONSTRAINT `person_work_FK_1`
        FOREIGN KEY (`person_id`)
        REFERENCES `person` (`id`),
    CONSTRAINT `person_work_FK_2`
        FOREIGN KEY (`personRole_id`)
        REFERENCES `personRole` (`id`),
    CONSTRAINT `person_work_FK_3`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` TEXT,
    `password` VARCHAR(512),
    `salt` VARCHAR(512),
    `mail` TEXT,
    `phone` TEXT,
    `admin` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- relatedSet
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `relatedSet`;

CREATE TABLE `relatedSet`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- task
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `taskType_id` INTEGER NOT NULL,
    `done` TINYINT(1),
    `start` DATE,
    `end` DATE,
    `comments` TEXT,
    `publicationGroup_id` INTEGER,
    `publication_id` INTEGER,
    `responsibleUser_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `task_FI_1` (`taskType_id`),
    INDEX `task_FI_2` (`publicationGroup_id`),
    INDEX `task_FI_3` (`publication_id`),
    INDEX `task_FI_4` (`responsibleUser_id`),
    CONSTRAINT `task_FK_1`
        FOREIGN KEY (`taskType_id`)
        REFERENCES `taskType` (`id`),
    CONSTRAINT `task_FK_2`
        FOREIGN KEY (`publicationGroup_id`)
        REFERENCES `publicationGroup` (`id`),
    CONSTRAINT `task_FK_3`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `task_FK_4`
        FOREIGN KEY (`responsibleUser_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- taskType
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `taskType`;

CREATE TABLE `taskType`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- partner
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `partner`;

CREATE TABLE `partner`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `name` TEXT,
    `address` TEXT,
    `person` TEXT,
    `mail` TEXT,
    `web` TEXT,
    `comments` TEXT,
    `phone1` TEXT,
    `phone2` TEXT,
    `phone3` TEXT,
    `fax` TEXT,
    `is_organization` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- imageSource
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `imageSource`;

CREATE TABLE `imageSource`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `partner_id` INTEGER COMMENT 'Anbieter Leitdruck',
    `catalogueSignature` TEXT,
    `catalogueUrl` TEXT COMMENT 'Link in den Katalog',
    `numFaksimiles` INTEGER COMMENT 'Anzahl Faksimiles',
    `extentAsOfCatalogue` TEXT COMMENT 'Umfang laut Katalog',
    `numPages` INTEGER COMMENT 'Umfang lt. Katalog',
    `imageUrl` TEXT COMMENT 'URL der Bilddigitalisate',
    `imageUrn` TEXT COMMENT 'URN der Bilddigitalisate',
    `license_id` INTEGER COMMENT 'Lizenz',
    PRIMARY KEY (`id`),
    INDEX `imageSource_FI_1` (`publication_id`),
    INDEX `imageSource_FI_2` (`license_id`),
    INDEX `imageSource_FI_3` (`partner_id`),
    CONSTRAINT `imageSource_FK_1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `imageSource_FK_2`
        FOREIGN KEY (`license_id`)
        REFERENCES `license` (`id`),
    CONSTRAINT `imageSource_FK_3`
        FOREIGN KEY (`partner_id`)
        REFERENCES `partner` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- textSource
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `textSource`;

CREATE TABLE `textSource`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `partner_id` INTEGER COMMENT 'Anbieter Textdigitalisate',
    `imageUrl` TEXT COMMENT 'URL der Textdigitalisate',
    `license_id` INTEGER COMMENT 'Lizenz',
    PRIMARY KEY (`id`),
    INDEX `textSource_FI_1` (`publication_id`),
    INDEX `textSource_FI_2` (`license_id`),
    INDEX `textSource_FI_3` (`partner_id`),
    CONSTRAINT `textSource_FK_1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `textSource_FK_2`
        FOREIGN KEY (`license_id`)
        REFERENCES `license` (`id`),
    CONSTRAINT `textSource_FK_3`
        FOREIGN KEY (`partner_id`)
        REFERENCES `partner` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- license
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `license`;

CREATE TABLE `license`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `url` TEXT,
    `applicable_to_image` TINYINT(1) DEFAULT 0 NOT NULL,
    `applicable_to_text` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publicationGroup
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publicationGroup`;

CREATE TABLE `publicationGroup`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
