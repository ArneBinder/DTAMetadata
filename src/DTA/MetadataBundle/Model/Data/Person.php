<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePerson;

class Person extends BasePerson {
    
    /**
     * @param Publication $publication
     * @return 1 if the author is the first author of the publication, 
     *         2 if second author, etc.
     *        -1 if not an author of this publication
     */
    public function getAuthorIndex($publication){
        
        $authorsOrderedById = \DTA\MetadataBundle\Model\Master\PersonPublicationQuery::create()
                ->joinWith("Publication")
                ->joinWith("Person")
                ->joinWith("Person.Personalname")
                ->joinWith("Personalname.Namefragment")
                ->filterByRole(\DTA\MetadataBundle\Model\Master\PersonPublicationPeer::ROLE_AUTHOR)
                ->orderBy('id', \Criteria::ASC);
        $personPublications = $publication->getPersonPublications($authorsOrderedById);
        
        $result = -1;
        $position = 0;
        foreach ($personPublications as $pp) {
            /* @var $pp DTA\MetadataBundle\Model\Master\PersonPublication */
            $position++;
            if($pp->getPersonId() === $this->getId()){
                $result = $position;
            }
        }
        
        return $result;
        
    }
    
    // overrides the basic function to guarantee order.
    public function getPersonalnames($criteria = NULL, PropelPDO $con = NULL) {
        $collection = parent::getPersonalnames();
        // sort by rank
        $collection->uasort(function($a, $b) {
                    return $a->getSortableRank() - $b->getSortableRank();
                });
        return $collection;
    }
    
    /**
     * Can be used to retrieve parts of the name, i.e. getNamePart("Vorname")
     * @param ENUM_VAL $part Name of the name fragement type (Vorname, Nachname, etc.)
     * @return Namefragment the name fragment or null if none found (in the first personal name)
     */
    public function getNamePart($part){
        
        $representativeName = $this->getRepresentativePersonalname();
        foreach ($representativeName->getNameFragments() as $nf) {
            if($nf->getType() === $part)
                return $nf;
        }      
        return NULL;
    }

    /**
     * Returns the first of the persons personal names
     * @return Personalname
     */
    public function getRepresentativePersonalname() {

        $personalNames = $this->getPersonalNames();
        return $personalNames->count() > 0 ? $personalNames[0] : "Kein Name gesetzt.";
    }

    /**
     * Used to generate a select box of existing person entities.
     * @return String Concatenation of all name fragments of the first name entity
     */
    public function getSelectBoxString() {

        $pn = $this->getPersonalnames();

        // if names exist, pick the first
        if (count($pn) > 0){
            return $pn[0]->__toString();
        } else {
            return $this->getGnd();
        }
    }
    
    /**
     * @param type $row an array containing 
     *     'gnd' => string or NULL  the integrated authority file key (http://en.wikipedia.org/wiki/Integrated_Authority_File) for the person
     *     'person' => string in either "lastname, firstname" format or "firstname lastname" format
     * example: array('publication_id' => '17223', 'person' => 'Anthus, Antonius', 'role' => 'synonym', 'gnd' => null))
     * @return \DTA\MetadataBundle\Model\Data\Person
     */
    public static function createFromArray($row){
        
        $name = new Personalname();
            
//        array('publication_id' => '17223', 'person' => 'Anthus, Antonius',
//         'role' => 'synonym', 'comma_position' => '7', 'space_position' => '8', 'gnd' => null)
                
        // remove the GND string part from the name
        if ($row['gnd'] !== NULL) {
            $row['person'] = str_replace('#'.$row['gnd'], "", $row['person']);
        } 

        // assume a comma is indicating "last name, first name" format
        if (strpos($row['person'], ',') !== FALSE) {
            $parts = explode(',', $row['person']);
            $name->addNamefragment(Namefragment::create($parts[0], NamefragmentPeer::TYPE_LAST_NAME));
            $name->addNamefragment(Namefragment::create($parts[1], NamefragmentPeer::TYPE_FIRST_NAME));
        // assume a space is indicating "first name last name" format
        } elseif (strpos($row['person'], ' ') !== FALSE) {
            $parts = explode(' ', $row['person']);
            $name->addNamefragment(Namefragment::create($parts[0], NamefragmentPeer::TYPE_FIRST_NAME));
            $name->addNamefragment(Namefragment::create($parts[1], NamefragmentPeer::TYPE_LAST_NAME));
        // no comma and no space indicates a last name only 
        } else {
            $name->addNamefragment(Namefragment::create($row['person'], NamefragmentPeer::TYPE_LAST_NAME));
        }
        
        // create person
        $person = new Person();
        $person->setGnd($row['gnd'])
                ->addPersonalname($name)
                ->save();
        return $person;
        
    }

    /**
     * Duplicate detection auxiliary function.
     * @param array $personData array containing
     *      'pnd' => string
     *      
     * @return boolean The described person can be considered equivalent to the person described by this object
     */
    public function match($personData){
        
        $firstNameFragment = $this->getNamePart(NamefragmentPeer::TYPE_FIRST_NAME);
        $firstName = $firstNameFragment === NULL ? NULL : $firstNameFragment->getName();

        $lastNameFragment = $this->getNamePart(NamefragmentPeer::TYPE_LAST_NAME);
        $lastName = $firstNameFragment === NULL ? NULL : $lastNameFragment->getName();
        
        // if person has gnd, things are easier
        if( $this->getGnd() !== NULL){

            // if the next person also has a gnd, simply compare
            if($personData['pnd'] !== NULL ){
                $result = $this->getGnd() === $personData['pnd'];
            } else {
                // otherwise compare last and first names
                $result = 
                    $personData['firstname'] === $firstName
                    && $personData['lastname'] === $lastName;
                $this->warnings[] = array('pnd added to author of book id='.$personData['id_book'] =>
                    'assuming '.$personData['lastname'].", ".$personData['firstname']." refers to ".
                    $lastName.", ".$firstName." #".$this->getGnd()); 
            }
        // if person has no gnd, only first/lastname can be used to detect same author
        } else {

            // if the next person also has a gnd, simply compare
            if($personData['pnd'] !== NULL ){
                // because the raw data query sorts by pnd, NULL values come last, so this must be a new person
                $result = FALSE;
            } else {
                // otherwise compare last and first names
                $result = 
                    $personData['firstname'] == $firstName 
                    && $personData['lastname']  == $lastName;
            }
        }
        return $result;
    }
}
