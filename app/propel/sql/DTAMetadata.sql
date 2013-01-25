
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- publication
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication`;

CREATE TABLE `publication`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `printRun` TEXT COMMENT 'Auflage',
    `printRunComment` TEXT,
    `edition` TEXT,
    `numPages` INTEGER,
    `numPagesNormed` INTEGER,
    `bibliographicCitation` TEXT,
    `title_id` INTEGER NOT NULL,
    `publishingCompany_id` INTEGER,
    `place_id` INTEGER,
    `dateSpecification_id` INTEGER,
    `relatedSet_id` INTEGER,
    `work_id` INTEGER NOT NULL,
    `publisher_id` INTEGER,
    `printer_id` INTEGER,
    `translator_id` INTEGER,
    `descendant_class` VARCHAR(100),
    PRIMARY KEY (`id`),
    INDEX `editionVon` (`work_id`),
    INDEX `fk_edition_herausgeber1` (`publisher_id`),
    INDEX `fk_edition_drucker1` (`printer_id`),
    INDEX `fk_schriftstueck_uebersetzer1` (`translator_id`),
    INDEX `fk_pub_relatedSet1` (`relatedSet_id`),
    INDEX `fk_publikation_verlag1` (`publishingCompany_id`),
    INDEX `fk_publikation_ort1` (`place_id`),
    INDEX `fk_publication_dateSpecification1` (`dateSpecification_id`),
    INDEX `FI_publikation_titel12` (`title_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- monograph
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `monograph`;

CREATE TABLE `monograph`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`publication_id`),
    INDEX `fk_publicationId` (`publication_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- essay
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `essay`;

CREATE TABLE `essay`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `printRun` TEXT COMMENT 'Auflage',
    `printRunComment` TEXT,
    `edition` TEXT,
    `numPages` INTEGER,
    `numPagesNormed` INTEGER,
    `bibliographicCitation` TEXT,
    `title_id` INTEGER NOT NULL,
    `publishingCompany_id` INTEGER,
    `place_id` INTEGER,
    `dateSpecification_id` INTEGER,
    `relatedSet_id` INTEGER,
    `work_id` INTEGER NOT NULL,
    `publisher_id` INTEGER,
    `printer_id` INTEGER,
    `translator_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `essay_I_1` (`work_id`),
    INDEX `essay_I_2` (`publisher_id`),
    INDEX `essay_I_3` (`printer_id`),
    INDEX `essay_I_4` (`translator_id`),
    INDEX `essay_I_5` (`relatedSet_id`),
    INDEX `essay_I_6` (`publishingCompany_id`),
    INDEX `essay_I_7` (`place_id`),
    INDEX `essay_I_8` (`dateSpecification_id`),
    INDEX `essay_I_9` (`title_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- magazine
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `magazine`;

CREATE TABLE `magazine`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `printRun` TEXT COMMENT 'Auflage',
    `printRunComment` TEXT,
    `edition` TEXT,
    `numPages` INTEGER,
    `numPagesNormed` INTEGER,
    `bibliographicCitation` TEXT,
    `title_id` INTEGER NOT NULL,
    `publishingCompany_id` INTEGER,
    `place_id` INTEGER,
    `dateSpecification_id` INTEGER,
    `relatedSet_id` INTEGER,
    `work_id` INTEGER NOT NULL,
    `publisher_id` INTEGER,
    `printer_id` INTEGER,
    `translator_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `magazine_I_1` (`work_id`),
    INDEX `magazine_I_2` (`publisher_id`),
    INDEX `magazine_I_3` (`printer_id`),
    INDEX `magazine_I_4` (`translator_id`),
    INDEX `magazine_I_5` (`relatedSet_id`),
    INDEX `magazine_I_6` (`publishingCompany_id`),
    INDEX `magazine_I_7` (`place_id`),
    INDEX `magazine_I_8` (`dateSpecification_id`),
    INDEX `magazine_I_9` (`title_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- series
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `series`;

CREATE TABLE `series`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `volume` TEXT,
    `printRun` TEXT COMMENT 'Auflage',
    `printRunComment` TEXT,
    `edition` TEXT,
    `numPages` INTEGER,
    `numPagesNormed` INTEGER,
    `bibliographicCitation` TEXT,
    `title_id` INTEGER NOT NULL,
    `publishingCompany_id` INTEGER,
    `place_id` INTEGER,
    `dateSpecification_id` INTEGER,
    `relatedSet_id` INTEGER,
    `work_id` INTEGER NOT NULL,
    `publisher_id` INTEGER,
    `printer_id` INTEGER,
    `translator_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `series_I_1` (`work_id`),
    INDEX `series_I_2` (`publisher_id`),
    INDEX `series_I_3` (`printer_id`),
    INDEX `series_I_4` (`translator_id`),
    INDEX `series_I_5` (`relatedSet_id`),
    INDEX `series_I_6` (`publishingCompany_id`),
    INDEX `series_I_7` (`place_id`),
    INDEX `series_I_8` (`dateSpecification_id`),
    INDEX `series_I_9` (`title_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- publishingCompany
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publishingCompany`;

CREATE TABLE `publishingCompany`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `gnd` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- volume
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `volume`;

CREATE TABLE `volume`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `volumeIndex` TEXT,
    `volumeIndexNumerical` INTEGER,
    `totalVolumes` INTEGER,
    `monograph_id` INTEGER NOT NULL,
    `monograph_publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_volume_monograph1` (`monograph_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- work
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `work`;

CREATE TABLE `work`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `status_id` INTEGER NOT NULL,
    `dateSpecification_id` INTEGER,
    `genre_id` INTEGER,
    `subgenre_id` INTEGER,
    `dwdsGenre_id` INTEGER,
    `dwdsSubgenre_id` INTEGER,
    `doi` TEXT,
    `comments` TEXT,
    `format` TEXT,
    `directoryName` TEXT,
    PRIMARY KEY (`id`),
    INDEX `fk_werk_status1` (`status_id`),
    INDEX `fk_werk_genre1` (`genre_id`),
    INDEX `fk_werk_genre2` (`subgenre_id`),
    INDEX `fk_werk_dwdsGenre2` (`dwdsGenre_id`),
    INDEX `fk_werk_dwdsGenre3` (`dwdsSubgenre_id`),
    INDEX `fk_work_dateSpecification1` (`dateSpecification_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- publication_publicationGroup
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publication_publicationGroup`;

CREATE TABLE `publication_publicationGroup`
(
    `publicationGroup_id` INTEGER NOT NULL,
    `publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`publicationGroup_id`,`publication_id`),
    INDEX `fk_publicationGroupValid` (`publication_id`),
    INDEX `fk_publicationValid` (`publicationGroup_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- corpus
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `corpus`;

CREATE TABLE `corpus`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- dwdsGenre
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `dwdsGenre`;

CREATE TABLE `dwdsGenre`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `childOf` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `fk_nachfolgender_arbeitsschritt` (`childOf`)
) ENGINE=MyISAM;

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
    INDEX `fk_nachfolgender_arbeitsschritt` (`childOf`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- partner
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `partner`;

CREATE TABLE `partner`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `adress` VARCHAR(255),
    `person` VARCHAR(255),
    `mail` VARCHAR(100),
    `web` VARCHAR(255),
    `comments` TEXT,
    `phone1` VARCHAR(50),
    `phone2` VARCHAR(50),
    `phone3` VARCHAR(50),
    `fax` VARCHAR(50),
    `log_last_change` DATETIME NOT NULL,
    `log_last_user` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `AI_id_book_locations` (`id`),
    INDEX `id_book_locations_2` (`id`),
    INDEX `name` (`name`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- relatedSet
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `relatedSet`;

CREATE TABLE `relatedSet`
(
    `id` INTEGER NOT NULL,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- source
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `source`;

CREATE TABLE `source`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `quality` TEXT,
    `name` TEXT,
    `comments` TEXT,
    `available` TINYINT(1),
    `signatur` VARCHAR(512),
    `library` TEXT,
    `libraryGnd` VARCHAR(1024),
    PRIMARY KEY (`id`),
    INDEX `AI_id_Fundstellen` (`id`),
    INDEX `id_Fundstellen_2` (`id`),
    INDEX `fk_quelle_edition1` (`publication_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- status
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `status`;

CREATE TABLE `status`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- task
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `taskType_id` INTEGER NOT NULL,
    `done` TINYINT(1),
    `start` DATETIME,
    `end` DATETIME,
    `comments` TEXT,
    `publicationGroup_id` INTEGER,
    `publication_id` INTEGER,
    `responsibleUser_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `id_task_2` (`id`),
    INDEX `endtime` (`end`),
    INDEX `starttime` (`start`),
    INDEX `active` (`done`),
    INDEX `closed_2` (`done`),
    INDEX `fk_aufgabe_arbeitsschritt1` (`taskType_id`),
    INDEX `fk_aufgabe_buchGruppe1` (`publicationGroup_id`),
    INDEX `fk_aufgabe_schriftstueck1` (`publication_id`),
    INDEX `fk_task_name1` (`responsibleUser_id`)
) ENGINE=MyISAM;

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
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `userName` VARCHAR(255),
    `passwordHash` TEXT,
    `name_id` INTEGER NOT NULL,
    `mail` TEXT,
    `phone` TEXT,
    PRIMARY KEY (`id`),
    INDEX `AI_id_user` (`id`),
    INDEX `id_user_2` (`id`),
    INDEX `name` (`userName`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- publicationGroup
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publicationGroup`;

CREATE TABLE `publicationGroup`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `AI_id_group` (`id`)
) ENGINE=MyISAM;

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
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- personalName
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `personalName`;

CREATE TABLE `personalName`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_personalName_person1` (`person_id`)
) ENGINE=MyISAM;

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
    INDEX `fk_name_namensart1` (`nameFragmentTypeId`),
    INDEX `fk_nameFragment_personalName1` (`personalName_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- nameFragmentType
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `nameFragmentType`;

CREATE TABLE `nameFragmentType`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- place
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `place`;

CREATE TABLE `place`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `gnd` VARCHAR(100),
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- title
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `title`;

CREATE TABLE `title`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

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
    INDEX `fk_title_titleid2` (`title_id`),
    INDEX `fk_titleFragmentType_type2` (`titleFragmentType_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- titleFragmentType
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `titleFragmentType`;

CREATE TABLE `titleFragmentType`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- author
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `author`;

CREATE TABLE `author`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `fk_autor_person1` (`person_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- author_work
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `author_work`;

CREATE TABLE `author_work`
(
    `work_id` INTEGER NOT NULL,
    `author_id` INTEGER NOT NULL,
    `author_person_id` INTEGER NOT NULL,
    `name_id` INTEGER NOT NULL,
    PRIMARY KEY (`work_id`,`author_id`,`author_person_id`),
    INDEX `fk_werk_has_autor_autor1` (`author_id`, `author_person_id`),
    INDEX `fk_werk_has_autor_werk1` (`work_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- person
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `person`;

CREATE TABLE `person`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `gnd` VARCHAR(100),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `dnb_UNIQUE` (`gnd`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- printer
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `printer`;

CREATE TABLE `printer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `fk_drucker_person1` (`person_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- publisher
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publisher`;

CREATE TABLE `publisher`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `fk_herausgeber_ist_person` (`person_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- translator
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `translator`;

CREATE TABLE `translator`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `fk_translator_person1` (`person_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
