<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1362051010.
 * Generated on 2013-02-28 12:30:10 by carlwitt
 */
class PropelMigration_1362051010
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

ALTER TABLE `person` CHANGE `gnd` `gnd` TEXT;

ALTER TABLE `user` CHANGE `userName` `userName` TEXT;

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

ALTER TABLE `person` CHANGE `gnd` `gnd` VARCHAR(100);

ALTER TABLE `user` CHANGE `userName` `userName` VARCHAR(255);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}