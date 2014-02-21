<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePublication;
use DTA\MetadataBundle\Model;

class Publication extends BasePublication
{
    /**
     * Retrieves the publication object (volume, chapter, article) which uses this object as core publication.
     */
    public function getSpecialization(){
        // camelcase version of the type e.g. type = VOLUME, becomes Volume
        $publicationType = ucwords(strtolower($this->getType()));
        $getter = 'get'.$publicationType;
        return $this->$getter();
    }
    
    /**
     * Used in the select or add control to add works on the fly.
     * @return string
     */
    public function getSelectBoxString(){
        return $this->getShortTitle();
    }
    
    /**
     * Used in displaying all publications (table row view behavior in the data schema definition) to select an author.
     * @return Personalname
     */
    public function getFirstAuthorName(){
        // TODO first person publication might not be the first author
        $personPublications = $this->getPersonPublications();
        if(count($personPublications) == 0 ) return NULL;
        $firstPersonPublication = $personPublications[0];
        $personalNames = $firstPersonPublication->getPerson()->getPersonalnames();
        if(count($personalNames) == 0 ) return NULL;
        return $personalNames[0];
    }
    
    /** Returns a single string combining all title fragments and a volume description. */
    public function getTitleString($withVolumeInformation = true){
    
        $title = $this->getTitle();
        $result = $title !== NULL ? $title->__toString() : "";
        if($withVolumeInformation && $this->getType() === PublicationPeer::TYPE_VOLUME ){
            $volume = $this->getVolume(); 
            if($volume === NULL) throw new \Exception("No volume entity related to volume publication ".$this->getId()." ".$this->getShortTitle());
            $result .= $volume->getVolumeSummary();
        }
        
        return $result;
    }
    
    /** Returns all tasks that are closed or open respectively. */
    public function getTasksByClosed($closed){
        
        return Model\Workflow\TaskQuery::create()
                ->filterByPublicationId($this->id)
                ->filterByClosed($closed)
                ->useTasktypeQuery()->orderByTreeLeft()->endUse()
//                ->orderByTasktypeId()
                ->find();
        
    }
    
    /** Returns a short title, suitable for displaying an overview. */
    public function getShortTitle(){
        
        $titleFragments = $this->getTitle()->getTitleFragments();
        // check if the title has a shortTitle fragment
        foreach ($titleFragments as $tf ){
            /* @var $tf Titlefragment */
            if($tf->getType() == TitlefragmentPeer::TYPE_SHORT_TITLE)
                return $tf->getName();
        }
        
        // no short title available
        return $this->getTitleString(false);
        
    }
    
}
