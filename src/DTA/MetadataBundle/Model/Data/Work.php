<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseWork;

class Work extends BaseWork
{
    /**
     * Used in the select or add control to add works on the fly.
     * @return string
     */
    public function getSelectBoxString(){
        return $this->getTitle();
    }
    
    /**
     * Used in displaying all works (table row view behavior in the data schema definition) to select an author.
     * @return string
     */
    public function getFirstAuthor(){
        $id = $this->getId();
        $query = PersonWorkQuery::create()->findAuthors($id);
        $author = $query->findOne();
        if($author === null)
            return "keine Angabe";
        else
            return PersonQuery::create()->findOneById($author->getPersonId())->getRepresentativePersonalname();
    }
}

