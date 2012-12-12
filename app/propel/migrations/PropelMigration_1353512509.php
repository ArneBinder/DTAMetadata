<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1353512509.
 * Generated on 2012-11-21 16:41:49 by stud
 */
class PropelMigration_1353512509
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

DROP TABLE IF EXISTS `titleType`;

ALTER TABLE `author` ADD CONSTRAINT `fk_autor_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `person` (`id`);

ALTER TABLE `author_work` ADD CONSTRAINT `fk_werk_has_autor_werk1`
    FOREIGN KEY (`work_id`)
    REFERENCES `work` (`id`);

ALTER TABLE `author_work` ADD CONSTRAINT `fk_werk_has_autor_autor1`
    FOREIGN KEY (`author_id`,`author_person_id`)
    REFERENCES `author` (`id`,`person_id`);

ALTER TABLE `corpus` ADD CONSTRAINT `fk_korpus_edition1`
    FOREIGN KEY (`writ_id`)
    REFERENCES `writ` (`id`);

ALTER TABLE `dateSpecification`
    ADD `comments_is_reconstructed` TINYINT(1) DEFAULT 0 AFTER `year_is_reconstructed`;

ALTER TABLE `dwdsGenre` ADD CONSTRAINT `fk_subgenre0`
    FOREIGN KEY (`childOf`)
    REFERENCES `dwdsGenre` (`id`);

ALTER TABLE `essay` ADD CONSTRAINT `fk_aufsatz_publikation1`
    FOREIGN KEY (`publication_id`)
    REFERENCES `publication` (`id`);

ALTER TABLE `genre` ADD CONSTRAINT `fk_subgenre`
    FOREIGN KEY (`childOf`)
    REFERENCES `genre` (`id`);

ALTER TABLE `magazine` ADD CONSTRAINT `fk_zeitschrift_publikation1`
    FOREIGN KEY (`publication_id`)
    REFERENCES `publication` (`id`);

ALTER TABLE `monograph` ADD CONSTRAINT `fk_monografie_publikation1`
    FOREIGN KEY (`publication_id`)
    REFERENCES `publication` (`id`);

ALTER TABLE `nameFragment` ADD CONSTRAINT `fk_name_namensart1`
    FOREIGN KEY (`nameFragmentTypeId`)
    REFERENCES `nameFragmentType` (`id`);

ALTER TABLE `nameFragment` ADD CONSTRAINT `fk_nameFragment_personalName1`
    FOREIGN KEY (`personalName_id`)
    REFERENCES `personalName` (`id`);

CREATE INDEX `id_book_locations_2` ON `partner` (`id`);

ALTER TABLE `personalName` ADD CONSTRAINT `fk_personalName_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `person` (`id`);

ALTER TABLE `printer` ADD CONSTRAINT `fk_drucker_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `person` (`id`);

ALTER TABLE `publication` ADD CONSTRAINT `fk_publikation_titel12`
    FOREIGN KEY (`title_id`)
    REFERENCES `title` (`id`);

ALTER TABLE `publication` ADD CONSTRAINT `fk_publikation_verlag1`
    FOREIGN KEY (`publishingCompany_id`)
    REFERENCES `publishingCompany` (`id`);

ALTER TABLE `publication` ADD CONSTRAINT `fk_publikation_ort1`
    FOREIGN KEY (`place_id`)
    REFERENCES `place` (`id`);

ALTER TABLE `publication` ADD CONSTRAINT `fk_publication_dateSpecification1`
    FOREIGN KEY (`dateSpecification_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `publisher` ADD CONSTRAINT `fk_herausgeber_ist_person`
    FOREIGN KEY (`person_id`)
    REFERENCES `person` (`id`);

ALTER TABLE `series` ADD CONSTRAINT `fk_reihe_publikation1`
    FOREIGN KEY (`publication_id`)
    REFERENCES `publication` (`id`);

CREATE INDEX `id_Fundstellen_2` ON `source` (`id`);

ALTER TABLE `source` ADD CONSTRAINT `fk_quelle_edition1`
    FOREIGN KEY (`writ_id`)
    REFERENCES `writ` (`id`);

CREATE INDEX `closed_2` ON `task` (`done`);

ALTER TABLE `task` ADD CONSTRAINT `fk_aufgabe_arbeitsschritt1`
    FOREIGN KEY (`taskType_id`)
    REFERENCES `taskType` (`id`);

ALTER TABLE `task` ADD CONSTRAINT `fk_aufgabe_buchGruppe1`
    FOREIGN KEY (`writGroup_id`)
    REFERENCES `writGroup` (`id`);

ALTER TABLE `task` ADD CONSTRAINT `fk_aufgabe_schriftstueck1`
    FOREIGN KEY (`writ_id`)
    REFERENCES `writ` (`id`);

ALTER TABLE `task` ADD CONSTRAINT `fk_task_name1`
    FOREIGN KEY (`responsibleUser_id`)
    REFERENCES `user` (`id`);

ALTER TABLE `titleFragment` ADD CONSTRAINT `fk_titleFragment_title`
    FOREIGN KEY (`title_id`)
    REFERENCES `title` (`id`);

ALTER TABLE `titleFragment` ADD CONSTRAINT `fk_name_titelFragmentArt1`
    FOREIGN KEY (`titleFragmentType_id`)
    REFERENCES `titleFragmentType` (`id`);

ALTER TABLE `translator` ADD CONSTRAINT `fk_translator_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `person` (`id`);

ALTER TABLE `user` DROP `created`;

ALTER TABLE `user` DROP `modified`;

CREATE INDEX `id_user_2` ON `user` (`id`);

ALTER TABLE `volume` ADD CONSTRAINT `fk_volume_monograph1`
    FOREIGN KEY (`monograph_id`,`monograph_publication_id`)
    REFERENCES `monograph` (`id`,`publication_id`);

ALTER TABLE `work` ADD CONSTRAINT `fk_werk_status1`
    FOREIGN KEY (`status_id`)
    REFERENCES `status` (`id`);

ALTER TABLE `work` ADD CONSTRAINT `fk_werk_genre1`
    FOREIGN KEY (`genre_id`)
    REFERENCES `genre` (`id`);

ALTER TABLE `work` ADD CONSTRAINT `fk_werk_genre2`
    FOREIGN KEY (`subgenre_id`)
    REFERENCES `genre` (`id`);

ALTER TABLE `work` ADD CONSTRAINT `fk_werk_dwdsGenre2`
    FOREIGN KEY (`dwdsGenre_id`)
    REFERENCES `dwdsGenre` (`id`);

ALTER TABLE `work` ADD CONSTRAINT `fk_werk_dwdsGenre3`
    FOREIGN KEY (`dwdsSubgenre_id`)
    REFERENCES `dwdsGenre` (`id`);

ALTER TABLE `work` ADD CONSTRAINT `fk_work_dateSpecification1`
    FOREIGN KEY (`dateSpecification_id`)
    REFERENCES `dateSpecification` (`id`);

ALTER TABLE `writ` ADD CONSTRAINT `editionVon`
    FOREIGN KEY (`work_id`)
    REFERENCES `work` (`id`);

ALTER TABLE `writ` ADD CONSTRAINT `fk_edition_herausgeber1`
    FOREIGN KEY (`publisher_id`)
    REFERENCES `publisher` (`id`);

ALTER TABLE `writ` ADD CONSTRAINT `fk_edition_drucker1`
    FOREIGN KEY (`printer_id`)
    REFERENCES `printer` (`id`);

ALTER TABLE `writ` ADD CONSTRAINT `fk_schriftstueck_uebersetzer1`
    FOREIGN KEY (`translator_id`)
    REFERENCES `translator` (`id`);

ALTER TABLE `writ` ADD CONSTRAINT `fk_schriftstueck_publikation1`
    FOREIGN KEY (`publication_id`)
    REFERENCES `publication` (`id`);

ALTER TABLE `writ` ADD CONSTRAINT `fk_writ_relatedSet1`
    FOREIGN KEY (`relatedSet_id`)
    REFERENCES `relatedSet` (`id`);

ALTER TABLE `writ_writGroup` ADD CONSTRAINT `fk_writGroup_has_writ_writGroup1`
    FOREIGN KEY (`writGroup_id`)
    REFERENCES `writGroup` (`id`);

ALTER TABLE `writ_writGroup` ADD CONSTRAINT `fk_writGroup_has_writ_writ1`
    FOREIGN KEY (`writ_id`)
    REFERENCES `writ` (`id`);

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

ALTER TABLE `author` DROP FOREIGN KEY `fk_autor_person1`;

ALTER TABLE `author_work` DROP FOREIGN KEY `fk_werk_has_autor_werk1`;

ALTER TABLE `author_work` DROP FOREIGN KEY `fk_werk_has_autor_autor1`;

ALTER TABLE `corpus` DROP FOREIGN KEY `fk_korpus_edition1`;

ALTER TABLE `dateSpecification` DROP `comments_is_reconstructed`;

ALTER TABLE `dwdsGenre` DROP FOREIGN KEY `fk_subgenre0`;

ALTER TABLE `essay` DROP FOREIGN KEY `fk_aufsatz_publikation1`;

ALTER TABLE `genre` DROP FOREIGN KEY `fk_subgenre`;

ALTER TABLE `magazine` DROP FOREIGN KEY `fk_zeitschrift_publikation1`;

ALTER TABLE `monograph` DROP FOREIGN KEY `fk_monografie_publikation1`;

ALTER TABLE `nameFragment` DROP FOREIGN KEY `fk_name_namensart1`;

ALTER TABLE `nameFragment` DROP FOREIGN KEY `fk_nameFragment_personalName1`;

DROP INDEX `id_book_locations_2` ON `partner`;

ALTER TABLE `personalName` DROP FOREIGN KEY `fk_personalName_person1`;

ALTER TABLE `printer` DROP FOREIGN KEY `fk_drucker_person1`;

ALTER TABLE `publication` DROP FOREIGN KEY `fk_publikation_titel12`;

ALTER TABLE `publication` DROP FOREIGN KEY `fk_publikation_verlag1`;

ALTER TABLE `publication` DROP FOREIGN KEY `fk_publikation_ort1`;

ALTER TABLE `publication` DROP FOREIGN KEY `fk_publication_dateSpecification1`;

ALTER TABLE `publisher` DROP FOREIGN KEY `fk_herausgeber_ist_person`;

ALTER TABLE `series` DROP FOREIGN KEY `fk_reihe_publikation1`;

ALTER TABLE `source` DROP FOREIGN KEY `fk_quelle_edition1`;

DROP INDEX `id_Fundstellen_2` ON `source`;

ALTER TABLE `task` DROP FOREIGN KEY `fk_aufgabe_arbeitsschritt1`;

ALTER TABLE `task` DROP FOREIGN KEY `fk_aufgabe_buchGruppe1`;

ALTER TABLE `task` DROP FOREIGN KEY `fk_aufgabe_schriftstueck1`;

ALTER TABLE `task` DROP FOREIGN KEY `fk_task_name1`;

DROP INDEX `closed_2` ON `task`;

ALTER TABLE `titleFragment` DROP FOREIGN KEY `fk_titleFragment_title`;

ALTER TABLE `titleFragment` DROP FOREIGN KEY `fk_name_titelFragmentArt1`;

ALTER TABLE `translator` DROP FOREIGN KEY `fk_translator_person1`;

DROP INDEX `id_user_2` ON `user`;

ALTER TABLE `user`
    ADD `created` DATETIME AFTER `phone`,
    ADD `modified` DATETIME AFTER `created`;

ALTER TABLE `volume` DROP FOREIGN KEY `fk_volume_monograph1`;

ALTER TABLE `work` DROP FOREIGN KEY `fk_werk_status1`;

ALTER TABLE `work` DROP FOREIGN KEY `fk_werk_genre1`;

ALTER TABLE `work` DROP FOREIGN KEY `fk_werk_genre2`;

ALTER TABLE `work` DROP FOREIGN KEY `fk_werk_dwdsGenre2`;

ALTER TABLE `work` DROP FOREIGN KEY `fk_werk_dwdsGenre3`;

ALTER TABLE `work` DROP FOREIGN KEY `fk_work_dateSpecification1`;

ALTER TABLE `writ` DROP FOREIGN KEY `editionVon`;

ALTER TABLE `writ` DROP FOREIGN KEY `fk_edition_herausgeber1`;

ALTER TABLE `writ` DROP FOREIGN KEY `fk_edition_drucker1`;

ALTER TABLE `writ` DROP FOREIGN KEY `fk_schriftstueck_uebersetzer1`;

ALTER TABLE `writ` DROP FOREIGN KEY `fk_schriftstueck_publikation1`;

ALTER TABLE `writ` DROP FOREIGN KEY `fk_writ_relatedSet1`;

ALTER TABLE `writ_writGroup` DROP FOREIGN KEY `fk_writGroup_has_writ_writGroup1`;

ALTER TABLE `writ_writGroup` DROP FOREIGN KEY `fk_writGroup_has_writ_writ1`;

CREATE TABLE `titleType`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}