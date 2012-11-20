<?php

namespace DTA\MetadataBundle\Model\Workflow\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'source' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Workflow.map
 */
class SourceTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Workflow.map.SourceTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('source');
        $this->setPhpName('Source');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Workflow\\Source');
        $this->setPackage('src.Workflow');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 10, null);
        $this->addForeignKey('WRIT_ID', 'WritId', 'INTEGER', 'writ', 'ID', true, null, null);
        $this->addColumn('QUALITY', 'Quality', 'LONGVARCHAR', false, null, null);
        $this->addColumn('NAME', 'Name', 'LONGVARCHAR', false, null, null);
        $this->addColumn('COMMENTS', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addColumn('AVAILABLE', 'Available', 'BOOLEAN', false, 1, null);
        $this->addColumn('SIGNATUR', 'Signatur', 'VARCHAR', false, 512, null);
        $this->addColumn('LIBRARY', 'Library', 'LONGVARCHAR', false, null, null);
        $this->addColumn('LIBRARYGND', 'Librarygnd', 'VARCHAR', false, 1024, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Writ', 'DTA\\MetadataBundle\\Model\\Publication\\Writ', RelationMap::MANY_TO_ONE, array('writ_id' => 'id', ), null, null);
    } // buildRelations()

} // SourceTableMap
