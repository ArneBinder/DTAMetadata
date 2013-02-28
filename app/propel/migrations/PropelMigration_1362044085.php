<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1362044085.
 * Generated on 2013-02-28 10:34:45 by carlwitt
 */
class PropelMigration_1362044085
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

ALTER TABLE `partner` CHANGE `name` `name` TEXT;

ALTER TABLE `partner` CHANGE `adress` `adress` TEXT;

ALTER TABLE `partner` CHANGE `person` `person` TEXT;

ALTER TABLE `partner` CHANGE `mail` `mail` TEXT;

ALTER TABLE `partner` CHANGE `web` `web` TEXT;

ALTER TABLE `partner` CHANGE `phone1` `phone1` TEXT;

ALTER TABLE `partner` CHANGE `phone2` `phone2` TEXT;

ALTER TABLE `partner` CHANGE `phone3` `phone3` TEXT;

ALTER TABLE `partner` CHANGE `fax` `fax` TEXT;

ALTER TABLE `person` CHANGE `gnd` `gnd` TEXT;

ALTER TABLE `place` CHANGE `gnd` `gnd` TEXT;

ALTER TABLE `source` CHANGE `signature` `signature` TEXT;

ALTER TABLE `source` CHANGE `libraryGnd` `libraryGnd` TEXT;

ALTER TABLE `user` CHANGE `userName` `userName` TEXT;

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

ALTER TABLE `partner` CHANGE `name` `name` VARCHAR(255);

ALTER TABLE `partner` CHANGE `adress` `adress` VARCHAR(255);

ALTER TABLE `partner` CHANGE `person` `person` VARCHAR(255);

ALTER TABLE `partner` CHANGE `mail` `mail` VARCHAR(100);

ALTER TABLE `partner` CHANGE `web` `web` VARCHAR(255);

ALTER TABLE `partner` CHANGE `phone1` `phone1` VARCHAR(50);

ALTER TABLE `partner` CHANGE `phone2` `phone2` VARCHAR(50);

ALTER TABLE `partner` CHANGE `phone3` `phone3` VARCHAR(50);

ALTER TABLE `partner` CHANGE `fax` `fax` VARCHAR(50);

ALTER TABLE `person` CHANGE `gnd` `gnd` VARCHAR(100);

ALTER TABLE `place` CHANGE `gnd` `gnd` VARCHAR(100);

ALTER TABLE `source` CHANGE `signature` `signature` VARCHAR(1024);

ALTER TABLE `source` CHANGE `libraryGnd` `libraryGnd` VARCHAR(1024);

ALTER TABLE `user` CHANGE `userName` `userName` VARCHAR(255);

ALTER TABLE `user`
    ADD `name_id` INTEGER NOT NULL AFTER `passwordHash`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}