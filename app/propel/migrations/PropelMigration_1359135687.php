<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1359135687.
 * Generated on 2013-01-25 18:41:27 by stud
 */
class PropelMigration_1359135687
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
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `konto`;

DROP TABLE IF EXISTS `profil`;

DROP TABLE IF EXISTS `writ`;

DROP TABLE IF EXISTS `writGroup`;

DROP TABLE IF EXISTS `writ_writGroup`;

ALTER TABLE `monograph` DROP PRIMARY KEY;

DROP INDEX `monograph_I_1` ON `monograph`;

DROP INDEX `monograph_I_2` ON `monograph`;

DROP INDEX `monograph_I_3` ON `monograph`;

DROP INDEX `monograph_I_4` ON `monograph`;

DROP INDEX `monograph_I_5` ON `monograph`;

DROP INDEX `monograph_I_6` ON `monograph`;

DROP INDEX `monograph_I_7` ON `monograph`;

DROP INDEX `monograph_I_8` ON `monograph`;

DROP INDEX `monograph_I_9` ON `monograph`;

ALTER TABLE `monograph`
    ADD `publication_id` INTEGER NOT NULL AUTO_INCREMENT AFTER `id`;

ALTER TABLE `monograph` DROP `printRun`;

ALTER TABLE `monograph` DROP `printRunComment`;

ALTER TABLE `monograph` DROP `edition`;

ALTER TABLE `monograph` DROP `numPages`;

ALTER TABLE `monograph` DROP `numPagesNormed`;

ALTER TABLE `monograph` DROP `bibliographicCitation`;

ALTER TABLE `monograph` DROP `title_id`;

ALTER TABLE `monograph` DROP `publishingCompany_id`;

ALTER TABLE `monograph` DROP `place_id`;

ALTER TABLE `monograph` DROP `dateSpecification_id`;

ALTER TABLE `monograph` DROP `relatedSet_id`;

ALTER TABLE `monograph` DROP `work_id`;

ALTER TABLE `monograph` DROP `publisher_id`;

ALTER TABLE `monograph` DROP `printer_id`;

ALTER TABLE `monograph` DROP `translator_id`;

ALTER TABLE `monograph` ADD PRIMARY KEY (`id`,`publication_id`);

CREATE INDEX `fk_publicationId` ON `monograph` (`publication_id`);

CREATE INDEX `id_book_locations_2` ON `partner` (`id`);

CREATE INDEX `id_Fundstellen_2` ON `source` (`id`);

CREATE INDEX `closed_2` ON `task` (`done`);

CREATE INDEX `id_user_2` ON `user` (`id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
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
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `monograph` DROP PRIMARY KEY;

DROP INDEX `fk_publicationId` ON `monograph`;

ALTER TABLE `monograph`
    ADD `printRun` TEXT AFTER `id`,
    ADD `printRunComment` TEXT AFTER `printRun`,
    ADD `edition` TEXT AFTER `printRunComment`,
    ADD `numPages` INTEGER AFTER `edition`,
    ADD `numPagesNormed` INTEGER AFTER `numPages`,
    ADD `bibliographicCitation` TEXT AFTER `numPagesNormed`,
    ADD `title_id` INTEGER NOT NULL AFTER `bibliographicCitation`,
    ADD `publishingCompany_id` INTEGER AFTER `title_id`,
    ADD `place_id` INTEGER AFTER `publishingCompany_id`,
    ADD `dateSpecification_id` INTEGER AFTER `place_id`,
    ADD `relatedSet_id` INTEGER AFTER `dateSpecification_id`,
    ADD `work_id` INTEGER NOT NULL AFTER `relatedSet_id`,
    ADD `publisher_id` INTEGER AFTER `work_id`,
    ADD `printer_id` INTEGER AFTER `publisher_id`,
    ADD `translator_id` INTEGER AFTER `printer_id`;

ALTER TABLE `monograph` DROP `publication_id`;

ALTER TABLE `monograph` ADD PRIMARY KEY (`id`);

CREATE INDEX `monograph_I_1` ON `monograph` (`work_id`);

CREATE INDEX `monograph_I_2` ON `monograph` (`publisher_id`);

CREATE INDEX `monograph_I_3` ON `monograph` (`printer_id`);

CREATE INDEX `monograph_I_4` ON `monograph` (`translator_id`);

CREATE INDEX `monograph_I_5` ON `monograph` (`relatedSet_id`);

CREATE INDEX `monograph_I_6` ON `monograph` (`publishingCompany_id`);

CREATE INDEX `monograph_I_7` ON `monograph` (`place_id`);

CREATE INDEX `monograph_I_8` ON `monograph` (`dateSpecification_id`);

CREATE INDEX `monograph_I_9` ON `monograph` (`title_id`);

DROP INDEX `id_book_locations_2` ON `partner`;

DROP INDEX `id_Fundstellen_2` ON `source`;

DROP INDEX `closed_2` ON `task`;

DROP INDEX `id_user_2` ON `user`;

CREATE TABLE `konto`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE `profil`
(
    `email` VARCHAR(255),
    `telephone` VARCHAR(255),
    `id` INTEGER NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE `writ`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `work_id` INTEGER NOT NULL,
    `publication_id` INTEGER NOT NULL,
    `publisher_id` INTEGER,
    `printer_id` INTEGER,
    `translator_id` INTEGER,
    `numPages` INTEGER,
    `relatedSet_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `editionVon` (`work_id`),
    INDEX `fk_edition_herausgeber1` (`publisher_id`),
    INDEX `fk_edition_drucker1` (`printer_id`),
    INDEX `fk_schriftstueck_uebersetzer1` (`translator_id`),
    INDEX `fk_schriftstueck_publikation1` (`publication_id`),
    INDEX `fk_writ_relatedSet1` (`relatedSet_id`)
) ENGINE=MyISAM;

CREATE TABLE `writGroup`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `AI_id_group` (`id`)
) ENGINE=MyISAM;

CREATE TABLE `writ_writGroup`
(
    `writGroup_id` INTEGER NOT NULL,
    `writ_id` INTEGER NOT NULL,
    PRIMARY KEY (`writGroup_id`,`writ_id`),
    INDEX `fk_writGroup_has_writ_writ1` (`writ_id`),
    INDEX `fk_writGroup_has_writ_writGroup1` (`writGroup_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}