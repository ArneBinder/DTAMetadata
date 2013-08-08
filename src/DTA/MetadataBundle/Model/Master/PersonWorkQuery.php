<?php

namespace DTA\MetadataBundle\Model\Master;

use DTA\MetadataBundle\Model\Master\om\BasePersonWorkQuery;

class PersonWorkQuery extends BasePersonWorkQuery {

    public function findAuthors($workId) {
        return $this
                        ->filterByPersonroleId(PersonrolePeer::getAuthorRoleId())
                        ->filterByWorkId($workId);
    }

}