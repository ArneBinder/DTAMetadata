<?php

namespace DTA\MetadataBundle\Model\reconstructed_flaggable;

/**
 * 
 * @author carlwitt
 */
interface ReconstructedFlaggableInterface {

    /**
     * Returns all columns that can be flagged as reconstructed.
     */
    public function getReconstructedFlaggableColumns();

    /**
     * Returns whether a column is flagged as reconstructed.
     * @param $column the PHP name of the column as defined in the schema
     */
    public function isReconstructedByName($column);

    /**
     * Returns all columns that are flagged as reconstructed.
     */
    public function getReconstructedFlaggedColumns();
}

?>
