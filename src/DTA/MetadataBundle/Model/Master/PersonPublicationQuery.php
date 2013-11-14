<?php

namespace DTA\MetadataBundle\Model\Master;

use DTA\MetadataBundle\Model\Master\om\BasePersonPublicationQuery;
use DTA\MetadataBundle\Model;

class PersonPublicationQuery extends BasePersonPublicationQuery
{
    public function findAuthors($publicationId) {
        return $this
            ->filterByPersonroleId(Model\Classification\PersonrolePeer::getAuthorRoleId())
            ->filterByPublicationId($publicationId);
    }
}
