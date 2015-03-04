<?php

class OrderByBehavior extends Behavior {

    //protected $useClass = null;

    public function queryMethods(){
        $sortingFunction = "sqlSort";
        $functionString = '$this';
        $sorted = false;
        $useCount = 0;
        $parameters = $this->getParameters();
        $keys = array();
        $values = array();

        // ensure order given by key1="1:use", key3="2:order", key2="3:filter", ...
        if(count($parameters)!==0 and strpos(end(array_keys($parameters)),':') !== false){
            ksort($parameters);
            foreach($parameters as $key => $value){
                $keyParts = explode(":",$key);
                $keys[] = $keyParts[1];
                $values[] = $value;
            }
            // if no order is given...
        }else{
            $keys = array_keys($parameters);
            $values = array_values($parameters);
        }
        $lastUseValue = "";
        for ($i = 0; $i < count($keys); $i++) {
            $key = strtolower($keys[$i]);
            $value = ucfirst($values[$i]);
            if($key==='order'){
                $functionString.='->orderBy'.$value.'($direction)';
                $sorted = true;
            }elseif($key==='use'){
                $lastUseValue = $value;
                $functionString.='->use'.$value.'Query()';
                $useCount++;
            }elseif($key==='filter'){
                $functionString.='->filterBy'.$value;
            }elseif($key==='enduse'){
                if(!$sorted){
                    $functionString.='->'.$sortingFunction.'($direction)';
                }
                $functionString.='->endUse()';
                $useCount--;
            }else{
                $tableName = $this->getTable()->getName();
                throw new Exception("Unknown key \"$key\" for \"$value\" in OrderByBehavior for table \"$tableName\"");
            }

        }
        if(!$sorted and $useCount>0){
            //$functionString = $lastUseValue.'Query::'.$sortingFunction.'('.$functionString.', $direction)';
            $functionString.='->'.$sortingFunction.'($direction)';
        }
        while($useCount > 0){
            $functionString.='->endUse()';
            $useCount--;
        }
        return $this->renderTemplate('orderBySQLSort',array('functionString' => $functionString));
    }


    // add interface implementation declaration
    public function queryFilter(&$script) {
        /*if($this->useClass!==null) {
            $package = end(explode('.',$this->getTable()->getPackage()));

            //$pattern = '/namespace (.+);/i';
            //$replace = 'namespace ${1};'."\n\n".' use DTA\MetadataBundle\Model\\'.$package.'\\' .  $this->useClass . ";";


            $pattern = '/abstract class/i';
            $replace = "\nuse DTA\\MetadataBundle\\Model\\$package\\" . $this->useClass . ";\nabstract class";
            $script = preg_replace($pattern, $replace, $script);
        }*/
    }
}