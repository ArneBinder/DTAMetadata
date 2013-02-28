<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BasePersonalname;

class Personalname extends BasePersonalname
{
    public function __toString(){
// TODO: If there should be any issue with the order, switch to the more complicated query structure.
//        NamefragmentQuery::create()
//                ->filterByPersonalnameId($this->getId())
//                ->orderByRank('asc')
//                ->find();
        
        $allNF = $this->getNamefragments();
        $result = "";
        foreach($allNF as $nameFragment){
            $result .= $nameFragment->getName() . " ";
        }
        return $result;
    }
 
}
