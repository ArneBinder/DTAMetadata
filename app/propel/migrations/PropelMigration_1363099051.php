<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1363099051.
 * Generated on 2013-03-12 15:37:31 by carlwitt
 */
class PropelMigration_1363099051
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

DROP TABLE IF EXISTS `author`;

DROP TABLE IF EXISTS `author_work`;

DROP TABLE IF EXISTS `printer`;

DROP TABLE IF EXISTS `publisher`;

DROP TABLE IF EXISTS `translator`;

ALTER TABLE `person_publication` CHANGE `id` `id` INTEGER NOT NULL AUTO_INCREMENT;

ALTER TABLE `person_work` CHANGE `id` `id` INTEGER NOT NULL AUTO_INCREMENT;

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

ALTER TABLE `person_publication` CHANGE `id` `id` INTEGER NOT NULL;

ALTER TABLE `person_work` CHANGE `id` `id` INTEGER NOT NULL;

CREATE TABLE `author`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `idx_autor_person1` (`person_id`)
) ENGINE=MyISAM;

CREATE TABLE `author_work`
(
    `work_id` INTEGER NOT NULL,
    `author_id` INTEGER NOT NULL,
    `author_person_id` INTEGER NOT NULL,
    `name_id` INTEGER NOT NULL,
    PRIMARY KEY (`work_id`,`author_id`,`author_person_id`),
    INDEX `idx_werk_has_autor_autor1` (`author_id`, `author_person_id`),
    INDEX `idx_werk_has_autor_werk1` (`work_id`)
) ENGINE=MyISAM;

CREATE TABLE `printer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `idx_drucker_person1` (`person_id`)
) ENGINE=MyISAM;

CREATE TABLE `publisher`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `idx_herausgeber_ist_person` (`person_id`)
) ENGINE=MyISAM;

CREATE TABLE `translator`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `person_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`person_id`),
    INDEX `idx_translator_person1` (`person_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}