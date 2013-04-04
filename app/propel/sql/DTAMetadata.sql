
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
    `printRun` TEXT COMMENT 'Bezeichnung der Auflage',
    `edition` TEXT,
    `editionNumerical` INTEGER,
    `numPages` INTEGER,
    `numPagesNormed` INTEGER,
    `bibliographicCitation` TEXT,
    `title_id` INTEGER NOT NULL,
    `publishingCompany_id` INTEGER,
    `place_id` INTEGER,
    `publicationDate_id` INTEGER,
    `firstPublicationDate_id` INTEGER,
    `relatedSet_id` INTEGER,
    `status_id` INTEGER NOT NULL,
    `work_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `editionVon` (`work_id`),
    INDEX `idx_pub_relatedSet1` (`relatedSet_id`),
    INDEX `idx_publikation_verlag1` (`publishingCompany_id`),
    INDEX `idx_publikation_ort1` (`place_id`),
    INDEX `idx_publication_publicationDateId1` (`publicationDate_id`),
    INDEX `idx_werk_status1` (`status_id`),
    INDEX `FI_publikation_titel12` (`title_id`),
    INDEX `FI_firstPublishedIn` (`firstPublicationDate_id`),
    CONSTRAINT `editionVon`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`),
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
    CONSTRAINT `fk_published`
        FOREIGN KEY (`publicationDate_id`)
        REFERENCES `dateSpecification` (`id`),
    CONSTRAINT `fk_firstPublishedIn`
        FOREIGN KEY (`firstPublicationDate_id`)
        REFERENCES `dateSpecification` (`id`),
    CONSTRAINT `fk_werk_status1`
        FOREIGN KEY (`status_id`)
        REFERENCES `status` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- monograph
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `monograph`;

CREATE TABLE `monograph`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_monograph_publicationId` (`publication_id`),
    CONSTRAINT `fk_monograph_publicationValid`
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
    `publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`publication_id`),
    INDEX `idx_essay_publicationId` (`publication_id`),
    CONSTRAINT `fk_essay_publicationValid`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- magazine
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `magazine`;

CREATE TABLE `magazine`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`publication_id`),
    INDEX `idx_magazine_publicationId` (`publication_id`),
    CONSTRAINT `fk_magazine_publicationValid`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- series
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `series`;

CREATE TABLE `series`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `volume` TEXT,
    PRIMARY KEY (`id`,`publication_id`),
    INDEX `idx_series_publicationId` (`publication_id`),
    CONSTRAINT `fk_series_publicationValid`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
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
    INDEX `idx_werk_genre1` (`genre_id`),
    INDEX `idx_werk_genre2` (`subgenre_id`),
    INDEX `idx_werk_dwdsGenre2` (`dwdsGenre_id`),
    INDEX `idx_werk_dwdsGenre3` (`dwdsSubgenre_id`),
    INDEX `idx_work_dateSpecification1` (`dateSpecification_id`),
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
    CONSTRAINT `fk_work_when_written`
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
    INDEX `publication_publicationGroup_FI_2` (`publication_id`),
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
    `name` TEXT,
    `adress` TEXT,
    `person` TEXT,
    `mail` TEXT,
    `web` TEXT,
    `comments` TEXT,
    `phone1` TEXT,
    `phone2` TEXT,
    `phone3` TEXT,
    `fax` TEXT,
    `log_last_change` DATETIME NOT NULL,
    `log_last_user` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `idx_book_locations_2` (`id`)
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
-- license
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `license`;

CREATE TABLE `license`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `url` TEXT,
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
    `catalogueSignature` TEXT,
    `catalogueUrl` TEXT COMMENT 'Link in den Katalog',
    `numFaksimiles` INTEGER,
    `numPages` INTEGER COMMENT 'Umfang lt. Katalog',
    `imageUrl` TEXT COMMENT 'URL der Bilddigitalisate',
    `imageUrn` TEXT COMMENT 'URN der Bilddigitalisate',
    `license_id` INTEGER COMMENT 'Lizenz',
    PRIMARY KEY (`id`),
    INDEX `idx_Fundstellen_3` (`id`),
    INDEX `idx_quelle_edition1` (`publication_id`),
    INDEX `idx_quelle_lizenz` (`license_id`),
    CONSTRAINT `fk_quelle_edition1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`),
    CONSTRAINT `fk_quelle_lizenz`
        FOREIGN KEY (`license_id`)
        REFERENCES `license` (`id`)
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
    `start` DATE,
    `end` DATE,
    `comments` TEXT,
    `publicationGroup_id` INTEGER,
    `publication_id` INTEGER,
    `responsibleUser_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `id_task_2` (`id`),
    INDEX `endtime` (`end`),
    INDEX `starttime` (`start`),
    INDEX `active` (`done`),
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
    CONSTRAINT `fk_task_responsibleUser1`
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
    `username` TEXT,
    `password` VARCHAR(512),
    `salt` VARCHAR(512),
    `mail` TEXT,
    `phone` TEXT,
    `admin` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `AI_id_user` (`id`)
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
    `sortable_rank` INTEGER,
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
    `gnd` TEXT,
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
-- person
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `person`;

CREATE TABLE `person`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `gnd` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `dnb_UNIQUE` (`gnd`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publicationRole
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publicationRole`;

CREATE TABLE `publicationRole`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- person_publication
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `person_publication`;

CREATE TABLE `person_publication`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publicationRole_id` INTEGER NOT NULL,
    `person_id` INTEGER NOT NULL,
    `publication_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_person_publication_publicationRole1_idx` (`publicationRole_id`),
    INDEX `fk_person_publication_person1_idx` (`person_id`),
    INDEX `fk_person_publication_publication1_idx` (`publication_id`),
    CONSTRAINT `fk_person_publication_publicationRole1`
        FOREIGN KEY (`publicationRole_id`)
        REFERENCES `publicationRole` (`id`),
    CONSTRAINT `fk_person_publication_person1`
        FOREIGN KEY (`person_id`)
        REFERENCES `person` (`id`),
    CONSTRAINT `fk_person_publication_publication1`
        FOREIGN KEY (`publication_id`)
        REFERENCES `publication` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- workRole
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `workRole`;

CREATE TABLE `workRole`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(1024),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- person_work
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `person_work`;

CREATE TABLE `person_work`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    `workRole_id` INTEGER NOT NULL,
    `work_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_person_publication_person1_idx` (`person_id`),
    INDEX `fk_person_publication_copy1_workRole1_idx` (`workRole_id`),
    INDEX `fk_person_publication_copy1_work1_idx` (`work_id`),
    CONSTRAINT `fk_person_publication_person10`
        FOREIGN KEY (`person_id`)
        REFERENCES `person` (`id`),
    CONSTRAINT `fk_person_work_workRole1`
        FOREIGN KEY (`workRole_id`)
        REFERENCES `workRole` (`id`),
    CONSTRAINT `fk_person_publication_copy1_work1`
        FOREIGN KEY (`work_id`)
        REFERENCES `work` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
