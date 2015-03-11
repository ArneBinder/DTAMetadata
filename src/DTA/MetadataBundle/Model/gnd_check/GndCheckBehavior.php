<?php
/**
 * Created by PhpStorm.
 * User: Arne
 * Date: 06.03.2015
 * Time: 19:06
 */

class GndCheckBehavior extends Behavior {
    public function objectMethods() {
        // default gnd column name
        $gndColumnName = 'gnd';
        if($this->getParameter('gnd_column_name') !== null){
            $gndColumnName = $this->getParameter('gnd_column_name');
        }

        if($this->getTable()->hasColumn($gndColumnName)
            and !array_key_exists('skip_table', $this->getParameters())) {
            return $this->renderTemplate('gndCheckTemplate', array('gndColumnName' => $gndColumnName));
        }else return "";
    }

}