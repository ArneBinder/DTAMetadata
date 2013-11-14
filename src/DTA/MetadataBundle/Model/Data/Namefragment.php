<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseNamefragment;
use DTA\MetadataBundle\Model;

class Namefragment extends BaseNamefragment
{
    /** array of format ('Vorname' => <id of namefragmenttype>, 'Nachname' => ...) */
    private static $nameFragmentTypeIds = null;
    /** calculates and returns $nameFragmentTypeIds */
    public static function getNameFragmentTypeIds(){
        // retrieve IDs of namefragment types only once
        if(Namefragment::$nameFragmentTypeIds === null){
            $nameFragmentTypes = Model\Classification\NamefragmenttypeQuery::create()->setFormatter('PropelArrayFormatter')->find();
            foreach ($nameFragmentTypes as $nft) {
                Namefragment::$nameFragmentTypeIds[$nft['Name']] = $nft['Id'];
            }
        }
        return Namefragment::$nameFragmentTypeIds;
    }
    
    // construct as e.g. new Namefragment('Vorname', '...')
    public function __construct($nameFragmentTypeName = "Nachname", $nameFragmentValue = NULL){
        $nftIds = Namefragment::getNameFragmentTypeIds();
        $this->setNamefragmenttypeid($nftIds[$nameFragmentTypeName]);
        $this->setName($nameFragmentValue);
    }
}
