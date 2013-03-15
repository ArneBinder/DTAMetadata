<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1363343498.
 * Generated on 2013-03-15 11:31:38 by carlwitt
 */
class PropelMigration_1363343498
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

DROP TABLE IF EXISTS `source`;

ALTER TABLE `monograph` DROP PRIMARY KEY;

ALTER TABLE `monograph` ADD PRIMARY KEY (`id`);

ALTER TABLE `relatedSet` CHANGE `id` `id` INTEGER NOT NULL AUTO_INCREMENT;

CREATE TABLE `license`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `url` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE `imageSource`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `catalogueSignature` TEXT,
    `catalogueUrl` TEXT COMMENT \'Link in den Katalog\',
    `numFaksimiles` INTEGER,
    `numPages` INTEGER COMMENT \'Umfang lt. Katalog\',
    `imageUrl` TEXT COMMENT \'URL der Bilddigitalisate\',
    `imageUrn` TEXT COMMENT \'URN der Bilddigitalisate\',
    `license_id` INTEGER COMMENT \'Lizenz\',
    PRIMARY KEY (`id`),
    INDEX `idx_Fundstellen_3` (`id`),
    INDEX `idx_quelle_edition1` (`publication_id`),
    INDEX `idx_quelle_lizenz` (`license_id`)
) ENGINE=MyISAM;

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

DROP TABLE IF EXISTS `license`;

DROP TABLE IF EXISTS `imageSource`;

ALTER TABLE `monograph` DROP PRIMARY KEY;

ALTER TABLE `monograph` ADD PRIMARY KEY (`id`,`publication_id`);

ALTER TABLE `relatedSet` CHANGE `id` `id` INTEGER NOT NULL;

CREATE TABLE `source`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `publication_id` INTEGER NOT NULL,
    `quality` TEXT,
    `name` TEXT,
    `comments` TEXT,
    `available` TINYINT(1),
    `signature` TEXT,
    `library` TEXT,
    `libraryGnd` TEXT,
    PRIMARY KEY (`id`),
    INDEX `idx_Fundstellen_3` (`id`),
    INDEX `idx_quelle_edition1` (`publication_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}