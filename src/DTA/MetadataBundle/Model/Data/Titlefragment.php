<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseTitlefragment;
use DTA\MetadataBundle\Model;

class Titlefragment extends BaseTitlefragment
{
    /** array of format ('Haupttitel' => <id of titlefragmenttype>, 'Untertitel' => ...) */
    private static $titleFragmentTypeIds = null;
    
    /** calculates and returns $titleFragmentTypeIds */
    public static function getTitleFragmentTypeIds(){
        // retrieve IDs of titlefragment types only once
        if(Titlefragment::$titleFragmentTypeIds === null){
            $titleFragmentTypes = Model\Classification\TitlefragmenttypeQuery::create()->setFormatter('PropelArrayFormatter')->find();
            foreach ($titleFragmentTypes as $tft) {
                Titlefragment::$titleFragmentTypeIds[$tft['Name']] = $tft['Id'];
            }
        }
        return Titlefragment::$titleFragmentTypeIds;
    }
    
    // construct as e.g. new Titlefragment('Haupttitel', 'Geschichte der frühen Neuzeit')
    public function __construct($titleFragmentTypeName = "Haupttitel", $titleFragmentValue = NULL){
        $tftIds = Titlefragment::getTitleFragmentTypeIds();
        $this->setTitlefragmenttypeid($tftIds[$titleFragmentTypeName]);
        $this->setName($titleFragmentValue);
    }
}
