
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
    INDEX `idx_edition_herausgeber1` (`publisher_id`),
    INDEX `idx_edition_drucker1` (`printer_id`),
    INDEX `idx_schriftstueck_uebersetzer1` (`translator_id`),
    INDEX `idx_pub_relatedSet1` (`relatedSet_id`),
    INDEX `idx_publikation_verlag1` (`publishingCompany_id`),
    INDEX `idx_publikation_ort1` (`place_id`),
    INDEX `idx_publication_dateSpecification1` (`dateSpecification_id`),
    INDEX `FI_publikation_titel12` (`title_id`),
    CONSTRAINT `editionVon`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`),
    CONSTRAINT `fk_edition_herausgeber1`
        FOREIGN KEY (`publisher_id`)
        REFERENCES `publisher` (`id`),
    CONSTRAINT `fk_edition_drucker1`
        FOREIGN KEY (`printer_id`)
        REFERENCES `printer` (`id`),
    CONSTRAINT `fk_schriftstueck_uebersetzer1`
        FOREIGN KEY (`translator_id`)
        REFERENCES `translator` (`id`),
    CONSTRAINT `fk_publication_relatedSet1`
        FOREIGN KEY (`relatedSet_id`)
        REFERENCES `relatedSet` (`id`),
    CONSTRAINT `fk_publikation_titel12`
        FOREIGN KEY (`title_id`)
        REFERENCES `title` (`id`),
    CONSTRAINT `fk_publikation_verlag1`
        FOREIGN KEY (`publishingCompany_id`)
        REFERENCES `publishingCompany` (`id`),
    CONSTRAINT `fk_publikation_ort1`
        FOREIGN KEY (`place_id`)
        REFERENCES `place` (`id`),
    CONSTRAINT `fk_publication_dateSpecification1`
        FOREIGN KEY (`dateSpecification_id`)
        REFERENCES `dateSpecification` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- monograph
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `monograph`;

CREATE TABLE `monograph`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`publication_id`),
    INDEX `idx_publicationId` (`publication_id`),
    CONSTRAINT `fk_publicationValid`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
) ENGINE=InnoDB;

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
    INDEX `essay_I_9` (`title_id`),
    CONSTRAINT `essay_FK_1`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`),
    CONSTRAINT `essay_FK_2`
        FOREIGN KEY (`publisher_id`)
        REFERENCES `publisher` (`id`),
    CONSTRAINT `essay_FK_3`
        FOREIGN KEY (`printer_id`)
        REFERENCES `printer` (`id`),
    CONSTRAINT `essay_FK_4`
        FOREIGN KEY (`translator_id`)
        REFERENCES `translator` (`id`),
    CONSTRAINT `essay_FK_5`
        FOREIGN KEY (`relatedSet_id`)
        REFERENCES `relatedSet` (`id`),
    CONSTRAINT `essay_FK_6`
        FOREIGN KEY (`title_id`)
        REFERENCES `title` (`id`),
    CONSTRAINT `essay_FK_7`
        FOREIGN KEY (`publishingCompany_id`)
        REFERENCES `publishingCompany` (`id`),
    CONSTRAINT `essay_FK_8`
        FOREIGN KEY (`place_id`)
        REFERENCES `place` (`id`),
    CONSTRAINT `essay_FK_9`
        FOREIGN KEY (`dateSpecification_id`)
        REFERENCES `dateSpecification` (`id`)
) ENGINE=InnoDB;

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
    INDEX `magazine_I_9` (`title_id`),
    CONSTRAINT `magazine_FK_1`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`),
    CONSTRAINT `magazine_FK_2`
        FOREIGN KEY (`publisher_id`)
        REFERENCES `publisher` (`id`),
    CONSTRAINT `magazine_FK_3`
        FOREIGN KEY (`printer_id`)
        REFERENCES `printer` (`id`),
    CONSTRAINT `magazine_FK_4`
        FOREIGN KEY (`translator_id`)
        REFERENCES `translator` (`id`),
    CONSTRAINT `magazine_FK_5`
        FOREIGN KEY (`relatedSet_id`)
        REFERENCES `relatedSet` (`id`),
    CONSTRAINT `magazine_FK_6`
        FOREIGN KEY (`title_id`)
        REFERENCES `title` (`id`),
    CONSTRAINT `magazine_FK_7`
        FOREIGN KEY (`publishingCompany_id`)
        REFERENCES `publishingCompany` (`id`),
    CONSTRAINT `magazine_FK_8`
        FOREIGN KEY (`place_id`)
        REFERENCES `place` (`id`),
    CONSTRAINT `magazine_FK_9`
        FOREIGN KEY (`dateSpecification_id`)
        REFERENCES `dateSpecification` (`id`)
) ENGINE=InnoDB;

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
    INDEX `series_I_9` (`title_id`),
    CONSTRAINT `series_FK_1`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`),
    CONSTRAINT `series_FK_2`
        FOREIGN KEY (`publisher_id`)
        REFERENCES `publisher` (`id`),
    CONSTRAINT `series_FK_3`
        FOREIGN KEY (`printer_id`)
        REFERENCES `printer` (`id`),
    CONSTRAINT `series_FK_4`
        FOREIGN KEY (`translator_id`)
        REFERENCES `translator` (`id`),
    CONSTRAINT `series_FK_5`
        FOREIGN KEY (`relatedSet_id`)
        REFERENCES `relatedSet` (`id`),
    CONSTRAINT `series_FK_6`
        FOREIGN KEY (`title_id`)
        REFERENCES `title` (`id`),
    CONSTRAINT `series_FK_7`
        FOREIGN KEY (`publishingCompany_id`)
        REFERENCES `publishingCompany` (`id`),
    CONSTRAINT `series_FK_8`
        FOREIGN KEY (`place_id`)
        REFERENCES `place` (`id`),
    CONSTRAINT `series_FK_9`
        FOREIGN KEY (`dateSpecification_id`)
        REFERENCES `dateSpecification` (`id`)
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

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
    INDEX `idx_volume_monograph1` (`monograph_id`),
    CONSTRAINT `fk_volume_monograph1`
        FOREIGN KEY (`monograph_id`)
        REFERENCES `monograph` (`id`)
) ENGINE=InnoDB;

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
    INDEX `idx_werk_status1` (`status_id`),
    INDEX `idx_werk_genre1` (`genre_id`),
    INDEX `idx_werk_genre2` (`subgenre_id`),
    INDEX `idx_werk_dwdsGenre2` (`dwdsGenre_id`),
    INDEX `idx_werk_dwdsGenre3` (`dwdsSubgenre_id`),
    INDEX `idx_work_dateSpecification1` (`dateSpecification_id`),
    CONSTRAINT `work_FK_1`
        FOREIGN KEY (`status_id`)
        REFERENCES `status` (`id`),
    CONSTRAINT `fk_werk_genre1`
        FOREIGN KEY (`genre_id`)
        REFERENCES `genre` (`id`),
    CONSTRAINT `fk_werk_genre2`
        FOREIGN KEY (`subgenre_id`)
        REFERENCES `genre` (`id`),
    CONSTRAINT `fk_werk_dwdsGenre2`
        FOREIGN KEY (`dwdsGenre_id`)
        REFERENCES `dwdsGenre` (`id`),
    CONSTRAINT `fk_werk_dwdsGenre3`
        FOREIGN KEY (`dwdsSubgenre_id`)
        REFERENCES `dwdsGenre` (`id`),
    CONSTRAINT `fk_work_dateSpecification1`
        FOREIGN KEY (`dateSpecification_id`)
        REFERENCES `dateSpecification` (`id`)
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
    INDEX `idx_publicationValid` (`publication_id`),
    INDEX `idx_publicationGroupValid` (`publicationGroup_id`),
    CONSTRAINT `fk_publicationGroupValid`
        FOREIGN KEY (`publicationGroup_id`)
        REFERENCES `publicationGroup` (`id`),
    CONSTRAINT `publication_publicationGroup_FK_2`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
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
-- dwdsGenre
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `dwdsGenre`;

CREATE TABLE `dwdsGenre`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `childOf` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `idx_nachfolgender_arbeitsschritt` (`childOf`),
    CONSTRAINT `fk_subgenre0`
        FOREIGN KEY (`childOf`)
        REFERENCES `dwdsGenre` (`id`)
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
    INDEX `idx_nachfolgender_arbeitsschritt` (`childOf`),
    CONSTRAINT `fk_subgenre`
        FOREIGN KEY (`childOf`)
        REFERENCES `genre` (`id`)
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- relatedSet
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `relatedSet`;

CREATE TABLE `relatedSet`
(
    `id` INTEGER NOT NULL,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

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
    INDEX `idx_quelle_edition1` (`publication_id`),
    CONSTRAINT `fk_quelle_edition1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- status
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `status`;

CREATE TABLE `status`
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
    INDEX `idx_aufgabe_arbeitsschritt1` (`taskType_id`),
    INDEX `idx_aufgabe_buchGruppe1` (`publicationGroup_id`),
    INDEX `idx_aufgabe_schriftstueck1` (`publication_id`),
    INDEX `idx_task_name1` (`responsibleUser_id`),
    CONSTRAINT `fk_aufgabe_arbeitsschritt1`
        FOREIGN KEY (`taskType_id`)
        REFERENCES `taskType` (`id`),
    CONSTRAINT `fk_aufgabe_buchGruppe1`
        FOREIGN KEY (`publicationGroup_id`)
        REFERENCES `publicationGroup` (`id`),
    CONSTRAINT `fk_aufgabe_schriftstueck1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `fk_task_name1`
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
) ENGINE=InnoDB;

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
    PRIMARY KEY (`id`),
    INDEX `idx_personalName_person1` (`person_id`),
    CONSTRAINT `fk_personalName_person1`
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
    INDEX `idx_name_namensart1` (`nameFragmentTypeId`),
    INDEX `idx_nameFragment_personalName1` (`personalName_id`),
    CONSTRAINT `fk_name_namensart1`
        FOREIGN KEY (`nameFragmentTypeId`)
        REFERENCES `nameFragmentType` (`id`),
    CONSTRAINT `fk_nameFragment_personalName1`
        FOREIGN KEY (`personalName_id`)
        REFERENCES `personalName` (`id`)
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
-- place
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `place`;

CREATE TABLE `place`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `gnd` VARCHAR(100),
    PRIMARY KEY (`id`)
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
    INDEX `idx_title_titleid2` (`title_id`),
    INDEX `idx_titleFragmentType_type2` (`titleFragmentType_id`),
    CONSTRAINT `fk_titleFragment_title`
        FOREIGN KEY (`title_id`)
        REFERENCES `title` (`id`),
    CONSTRAINT `fk_name_titelFragmentArt1`
        FOREIGN KEY (`titleFragmentType_id`)
        REFERENCES `titleFragmentType` (`id`)
) ENGINE=InnoDB;

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
-- author
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `author`;

CREATE TABLE `author`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `idx_autor_person1` (`person_id`),
    CONSTRAINT `fk_autor_person1`
        FOREIGN KEY (`person_id`)
        REFERENCES `person` (`id`)
) ENGINE=InnoDB;

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
    INDEX `idx_werk_has_autor_autor1` (`author_id`, `author_person_id`),
    INDEX `idx_werk_has_autor_werk1` (`work_id`),
    CONSTRAINT `fk_werk_has_autor_werk1`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`),
    CONSTRAINT `fk_werk_has_autor_autor1`
        FOREIGN KEY (`author_id`,`author_person_id`)
        REFERENCES `author` (`id`,`person_id`)
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- printer
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `printer`;

CREATE TABLE `printer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `idx_drucker_person1` (`person_id`),
    CONSTRAINT `fk_drucker_person1`
        FOREIGN KEY (`person_id`)
        REFERENCES `person` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publisher
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publisher`;

CREATE TABLE `publisher`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `idx_herausgeber_ist_person` (`person_id`),
    CONSTRAINT `fk_herausgeber_ist_person`
        FOREIGN KEY (`person_id`)
        REFERENCES `person` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- translator
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `translator`;

CREATE TABLE `translator`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `idx_translator_person1` (`person_id`),
    CONSTRAINT `fk_translator_person1`
        FOREIGN KEY (`person_id`)
        REFERENCES `person` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
