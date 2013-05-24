<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BasePersonWorkQuery;

class PersonWorkQuery extends BasePersonWorkQuery {

    public function findAuthors($workId) {
        return $this
                        ->filterByPersonroleId(PersonrolePeer::getAuthorRoleId())
                        ->filterByWorkId($workId);
    }

}
