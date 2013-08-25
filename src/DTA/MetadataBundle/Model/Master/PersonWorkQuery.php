<?php

namespace DTA\MetadataBundle\Model\Master;

use DTA\MetadataBundle\Model\Master\om\BasePersonWorkQuery;
use DTA\MetadataBundle\Model\Classification;

class PersonWorkQuery extends BasePersonWorkQuery {

    public function findAuthors($workId) {
        return $this
            ->filterByPersonroleId(Classification\PersonrolePeer::getAuthorRoleId())
            ->filterByWorkId($workId);
    }

}