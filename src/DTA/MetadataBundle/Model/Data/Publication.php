<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePublication;
use DTA\MetadataBundle\Model;

class Publication extends BasePublication
{
    // the concrete publication object (PublicationM, PublicationJ, etc.) for which this provides the basic data fields
    private $wrapperPublication = null;
    // only the class name (NO full qualification with namespaces, e.g. PublicationM)
    private $wrapperPublicationClass = "";
    
    /**
     * Retrieves the publication object (volume, chapter, article) which uses this object.
     */
    public function getSpecialization(){
        switch($this->getType()){
            case PublicationPeer::TYPE_ARTICLE:
                return $this->getArticles()->pop();
            case PublicationPeer::TYPE_CHAPTER:
                return $this->getChapters()->pop();
            case PublicationPeer::TYPE_VOLUME:
                return $this->getVolumes()->pop();
            default:
                return $this;   
        }
    }
    
    /**
     * Used in the select or add control to add works on the fly.
     * @return string
     */
    public function getSelectBoxString(){
        return $this->getTitle();
    }
    
    /**
     * Used in displaying all publications (table row view behavior in the data schema definition) to select an author.
     * @return string
     */
    public function getFirstAuthor(){
        // TODO first person publication might not be the first author
        $personPublications = $this->getPersonPublications();
        if(count($personPublications) == 0 ) return NULL;
        $firstPersonPublication = $personPublications[0];
        $personalNames = $firstPersonPublication->getPerson()->getPersonalnames();
        if(count($personalNames) == 0 ) return NULL;
        return $personalNames[0];
    }
    
    /** Returns a single string combining all title fragments and a volume description. */
    public function getTitleString(){
    
        $title = $this->getTitle();
        $result = $title !== NULL ? $title->__toString() : "";
        if($this->getType() === PublicationPeer::TYPE_VOLUME ){
            $volumes = $this->getVolumes();
            $result .= $volumes->count() > 0 ? " (" . $volumes[0]->getVolumeNumeric() . ") " . $volumes[0]->getVolumeDescription() : "";
        }
        
        return $result;
        
    }
}
