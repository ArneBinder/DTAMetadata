<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1363362863.
 * Generated on 2013-03-15 16:54:23 by carlwitt
 */
class PropelMigration_1363362863
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

ALTER TABLE `publication`
    ADD `imageSource_id` INTEGER AFTER `work_id`;

CREATE INDEX `idx_publication_imageSource` ON `publication` (`imageSource_id`);

ALTER TABLE `user` DROP `name_id`;

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

DROP INDEX `idx_publication_imageSource` ON `publication`;

ALTER TABLE `publication` DROP `imageSource_id`;

ALTER TABLE `user`
    ADD `name_id` INTEGER NOT NULL AFTER `salt`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}