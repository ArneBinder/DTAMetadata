<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BaseDatespecification;

class Datespecification extends BaseDatespecification {

    public function __toString() {
        $result = "";
        $result .= $this->getYear();

        if ($this->getYearIsReconstructed())
            $result = "[" . $result . "]";

        return $result;
    }

}
