<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePublication;

class Publication extends BasePublication
{
    public $publicationTypes = array(
        'PublicationM',
        'PublicationDm',
        'PublicationMm',
        'PublicationDs',
        'PublicationMs',
        'PublicationJa',
        'PublicationMms',
        'PublicationJ',
    );
    
    // the concrete publication object (PublicationM, PublicationJ, etc.) for which this provides the basic data fields
    private $wrapperPublication = null;
    // only the class name (NO full qualification with namespaces, e.g. PublicationM)
    private $wrapperPublicationClass = "";
    
    /**
     * Retrieves the publication(M/J/Ms/etc.) object which uses this object.
     */
    private function getDynamicType(){
        foreach($this->publicationTypes as $pubType){ 
            $getter = "get".$pubType."s";
            if( $this->$getter()->count() == 1){
                // there must be exactly one wrapping publication object for each publication!
                $this->wrapperPublication = $this->$getter()->pop();
                $this->wrapperPublicationClass = $pubType;
            }
        }
    }
    
    public function getWrapperPublication(){
        if($this->wrapperPublication === null){
            // find out whether this publication is used by a PublicationM, or a PublicationJ, or etc. object
            $this->getDynamicType();
        }
        return $this->wrapperPublication;
    }
    
    public function getWrapperPublicationClass(){
        if($this->wrapperPublication === null){
            $this->getDynamicType();
        }
        return $this->wrapperPublicationClass;
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
        $id = $this->getId();
        $query = Master\PersonPublicationQuery::create()->findAuthors($id);
        $author = $query->findOne();
        if($author === null)
            return "keine Angabe";
        else
            return PersonQuery::create()->findOneById($author->getPersonId())->getRepresentativePersonalname();
    }
}
