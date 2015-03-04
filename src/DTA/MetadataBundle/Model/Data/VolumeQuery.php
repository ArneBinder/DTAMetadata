<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseVolumeQuery;

class VolumeQuery extends BaseVolumeQuery
{
    /*
        public function orderByParentPublicationShortTitle($direction){
            return PublicationQuery::sqlSort($this->usePublicationQuery(),$direction)->endUse();
        }
    */

        public function orderByParentPublicationShortTitle($direction){
            return $this->usePublicationQuery()->sqlSort($direction)->endUse();
        }

}
