<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BaseTitle;

class Title extends BaseTitle
{
    public function __toString(){
// TODO: If there should be any issue with the order, switch to the more complicated query structure.
//        NamefragmentQuery::create()
//                ->filterByPersonalnameId($this->getId())
//                ->orderByRank('asc')
//                ->find();
        
        $allNF = $this->getTitlefragments();
        $result = "";
        foreach($allNF as $nameFragment){
            $result .= $nameFragment->getName() . " ";
        }
        return $result;
    }
}
