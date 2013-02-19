<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1361207799.
 * Generated on 2013-02-18 18:16:39 by carlwitt
 */
class PropelMigration_1361207799
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

ALTER TABLE `essay` CHANGE `numericalEdition` `editionNumerical` TEXT;

CREATE INDEX `essay_I_8` ON `essay` (`dateSpecification_id`);

ALTER TABLE `magazine` CHANGE `numericalEdition` `editionNumerical` TEXT;

CREATE INDEX `magazine_I_8` ON `magazine` (`dateSpecification_id`);

CREATE INDEX `id_book_locations_2` ON `partner` (`id`);

ALTER TABLE `publication` CHANGE `numericalEdition` `editionNumerical` TEXT;

CREATE INDEX `idx_publication_dateSpecification1` ON `publication` (`dateSpecification_id`);

ALTER TABLE `series` CHANGE `numericalEdition` `editionNumerical` TEXT;

CREATE INDEX `series_I_8` ON `series` (`dateSpecification_id`);

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

DROP INDEX `essay_I_8` ON `essay`;

ALTER TABLE `essay` CHANGE `editionNumerical` `numericalEdition` TEXT;

DROP INDEX `magazine_I_8` ON `magazine`;

ALTER TABLE `magazine` CHANGE `editionNumerical` `numericalEdition` TEXT;

DROP INDEX `id_book_locations_2` ON `partner`;

DROP INDEX `idx_publication_dateSpecification1` ON `publication`;

ALTER TABLE `publication` CHANGE `editionNumerical` `numericalEdition` TEXT;

DROP INDEX `series_I_8` ON `series`;

ALTER TABLE `series` CHANGE `editionNumerical` `numericalEdition` TEXT;

DROP INDEX `id_Fundstellen_2` ON `source`;

DROP INDEX `closed_2` ON `task`;

DROP INDEX `id_user_2` ON `user`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}