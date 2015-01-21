<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePublication;
use DTA\MetadataBundle\Model;
use \Propel;
use \PropelCollection;

class Publication extends BasePublication
{
    public static function create($publicationType){
        // check type is valid
        $validTypes = PublicationPeer::getValueSet(PublicationPeer::TYPE);
        if( FALSE === array_search($publicationType, $validTypes)){
            throw new \Exception("Cannot create publication of type $publicationType, use one of ".implode(', ',$validTypes));
        }
        
        $basepublication = new Publication();
        $basepublication->setType($publicationType);
        
        // the type string equals the unqualified class name
        $className = 'DTA\\MetadataBundle\\Model\\Data\\' . $publicationType;
        $specializedPublication = new $className();
        $specializedPublication->setPublication($basepublication);
        return $specializedPublication;
        
    }
    
    /**
     * Retrieves the publication object (volume, chapter, article) which uses this object as core publication.
     */
    public function getSpecialization(){
        // camelcase version of the type e.g. type = VOLUME, becomes Volume
        $publicationType = ucwords(strtolower($this->getType()));
        $getter = 'get'.$publicationType;
        if(method_exists($this, $getter)){
            return $this->$getter();
        } else {
            return $this;
        }
    }
    
    /** @return the class name (WITHOUT full qualification) of the specialization class. Might be Publication in case there exists no extra class to represent the publications type. */
    public function getSpecializationClassName(){
        $specializedClassNameParts = explode('\\', get_class($this->getSpecialization())); // fully qualified
        return array_pop($specializedClassNameParts);
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

    public function deleteFromTree(){
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }
        $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        $con->beginTransaction();
        try {
            // nested_set behavior
            if ($this->isRoot()) {
                throw new PropelException('Deletion of a root node is disabled for nested sets. Use PublicationPeer::deleteTree($scope) instead to delete an entire tree');
            }

            // nested_set behavior
            if ($this->isInTree()) {
                // fill up the room that was used by the node
                PublicationPeer::shiftRLValues(-2, $this->getRightValue() + 1, null, $this->getScopeValue(), $con);

                $this->setTreeLeft(null)
                    ->setTreeRight(null)
                    ->setTreeLevel(null)
                    ->setScopeValue($this->getId())
                    ->save($con);

                //$query = PublicationQuery::create()
                 //   ->filterByPrimaryKey($this->getPrimaryKey());
                //$query->setScopeValue($this->getId())->save($con);
            }
            $con->commit();

            //$this->setScopeValue($this->getId())->save($con);
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }
}
