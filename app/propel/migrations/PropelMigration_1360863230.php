<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1360863230.
 * Generated on 2013-02-14 18:33:50 by carlwitt
 */
class PropelMigration_1360863230
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

ALTER TABLE `dateSpecification` CHANGE `comments_is_reconstructed` `year_is_reconstructed` TINYINT(1) DEFAULT 0;

ALTER TABLE `essay` DROP FOREIGN KEY `essay_FK_9`;

ALTER TABLE `essay` CHANGE `dateSpecification_id` `publicationDate_id` INTEGER;

ALTER TABLE `essay`
    ADD `originDate_id` INTEGER AFTER `publicationDate_id`;

CREATE INDEX `essay_I_10` ON `essay` (`publicationDate_id`);

CREATE INDEX `essay_I_11` ON `essay` (`originDate_id`);

ALTER TABLE `essay` ADD CONSTRAINT `essay_FK_9`
    FOREIGN KEY (`publicationDate_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `essay` ADD CONSTRAINT `essay_FK_10`
    FOREIGN KEY (`originDate_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `magazine` DROP FOREIGN KEY `magazine_FK_9`;

ALTER TABLE `magazine` CHANGE `dateSpecification_id` `publicationDate_id` INTEGER;

ALTER TABLE `magazine`
    ADD `originDate_id` INTEGER AFTER `publicationDate_id`;

CREATE INDEX `magazine_I_10` ON `magazine` (`publicationDate_id`);

CREATE INDEX `magazine_I_11` ON `magazine` (`originDate_id`);

ALTER TABLE `magazine` ADD CONSTRAINT `magazine_FK_9`
    FOREIGN KEY (`publicationDate_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `magazine` ADD CONSTRAINT `magazine_FK_10`
    FOREIGN KEY (`originDate_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `monograph` DROP FOREIGN KEY `fk_publicationValid`;

CREATE INDEX `id_book_locations_2` ON `partner` (`id`);

ALTER TABLE `publication` DROP FOREIGN KEY `fk_publication_dateSpecification1`;

ALTER TABLE `publication` CHANGE `dateSpecification_id` `publicationDate_id` INTEGER;

ALTER TABLE `publication`
    ADD `originDate_id` INTEGER AFTER `publicationDate_id`;

CREATE INDEX `FI_publication_dateSpecification1` ON `publication` (`publicationDate_id`);

CREATE INDEX `FI_publication_dateSpecification2` ON `publication` (`originDate_id`);

ALTER TABLE `publication` ADD CONSTRAINT `fk_publication_dateSpecification1`
    FOREIGN KEY (`publicationDate_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `publication` ADD CONSTRAINT `fk_publication_dateSpecification2`
    FOREIGN KEY (`originDate_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `series` DROP FOREIGN KEY `series_FK_9`;

ALTER TABLE `series` CHANGE `dateSpecification_id` `publicationDate_id` INTEGER;

ALTER TABLE `series`
    ADD `originDate_id` INTEGER AFTER `publicationDate_id`;

CREATE INDEX `series_I_10` ON `series` (`publicationDate_id`);

CREATE INDEX `series_I_11` ON `series` (`originDate_id`);

ALTER TABLE `series` ADD CONSTRAINT `series_FK_9`
    FOREIGN KEY (`publicationDate_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `series` ADD CONSTRAINT `series_FK_10`
    FOREIGN KEY (`originDate_id`)
    REFERENCES `dateSpecification` (`id`);

CREATE INDEX `id_Fundstellen_2` ON `source` (`id`);

CREATE INDEX `closed_2` ON `task` (`done`);

CREATE INDEX `id_user_2` ON `user` (`id`);

ALTER TABLE `work` DROP FOREIGN KEY `work_FK_1`;

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

ALTER TABLE `dateSpecification` CHANGE `year_is_reconstructed` `comments_is_reconstructed` TINYINT(1) DEFAULT 0;

ALTER TABLE `essay` DROP FOREIGN KEY `essay_FK_10`;

ALTER TABLE `essay` DROP FOREIGN KEY `essay_FK_9`;

DROP INDEX `essay_I_10` ON `essay`;

DROP INDEX `essay_I_11` ON `essay`;

ALTER TABLE `essay` CHANGE `publicationDate_id` `dateSpecification_id` INTEGER;

ALTER TABLE `essay` DROP `originDate_id`;

ALTER TABLE `essay` ADD CONSTRAINT `essay_FK_9`
    FOREIGN KEY (`dateSpecification_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `magazine` DROP FOREIGN KEY `magazine_FK_10`;

ALTER TABLE `magazine` DROP FOREIGN KEY `magazine_FK_9`;

DROP INDEX `magazine_I_10` ON `magazine`;

DROP INDEX `magazine_I_11` ON `magazine`;

ALTER TABLE `magazine` CHANGE `publicationDate_id` `dateSpecification_id` INTEGER;

ALTER TABLE `magazine` DROP `originDate_id`;

ALTER TABLE `magazine` ADD CONSTRAINT `magazine_FK_9`
    FOREIGN KEY (`dateSpecification_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `monograph` ADD CONSTRAINT `fk_publicationValid`
    FOREIGN KEY (`publication_id`)
    REFERENCES `publication` (`id`);

DROP INDEX `id_book_locations_2` ON `partner`;

ALTER TABLE `publication` DROP FOREIGN KEY `fk_publication_dateSpecification2`;

ALTER TABLE `publication` DROP FOREIGN KEY `fk_publication_dateSpecification1`;

DROP INDEX `FI_publication_dateSpecification1` ON `publication`;

DROP INDEX `FI_publication_dateSpecification2` ON `publication`;

ALTER TABLE `publication` CHANGE `publicationDate_id` `dateSpecification_id` INTEGER;

ALTER TABLE `publication` DROP `originDate_id`;

ALTER TABLE `publication` ADD CONSTRAINT `fk_publication_dateSpecification1`
    FOREIGN KEY (`dateSpecification_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `series` DROP FOREIGN KEY `series_FK_10`;

ALTER TABLE `series` DROP FOREIGN KEY `series_FK_9`;

DROP INDEX `series_I_10` ON `series`;

DROP INDEX `series_I_11` ON `series`;

ALTER TABLE `series` CHANGE `publicationDate_id` `dateSpecification_id` INTEGER;

ALTER TABLE `series` DROP `originDate_id`;

ALTER TABLE `series` ADD CONSTRAINT `series_FK_9`
    FOREIGN KEY (`dateSpecification_id`)
    REFERENCES `dateSpecification` (`id`);

DROP INDEX `id_Fundstellen_2` ON `source`;

DROP INDEX `closed_2` ON `task`;

DROP INDEX `id_user_2` ON `user`;

ALTER TABLE `work` ADD CONSTRAINT `work_FK_1`
    FOREIGN KEY (`status_id`)
    REFERENCES `status` (`id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}