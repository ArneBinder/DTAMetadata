<?php
/**
 * Created by PhpStorm.
 * User: Arne
 * Date: 06.03.2015
 * Time: 19:06
 */

class GndCheckBehavior extends Behavior {
    public function objectMethods() {
        return $this->renderTemplate('gndCheckTemplate');
    }

}