
/**
* order function for get<?php echo $elementName;?> accessor
*/
public function orderBy<?php echo $elementName;?>($direction){
    return $this<?php
                        foreach($useClasses as $useClass){
                            echo '->use'.$useClass.'Query()';
                        }
                        echo '->sqlSort($direction)';
                        foreach($useClasses as $useClass){
                            echo '->endUse()';
                        }

                    ?>;
}


