/** 
 * Selects one of many related entities 
 */
 
<?php 
    $representativeFunctionName = 'getRepresentative' . $className;
    $pluralizer = new \StandardEnglishPluralizer();
    $pluralizedClassname = $pluralizer->getPluralForm($className);
    
    $countFunction = '$this->count' . $pluralizedClassname;
    $relatedEntitiesGetter = '$this->get' . $pluralizedClassname;
    $filterFunctionName = 'filterBy' . $modelClassName . 'Id';
?>
public function <?php echo $representativeFunctionName;?>(){
    
    if (<?php echo $countFunction;?>() > 0) {

        $pn = <?php echo $relatedEntitiesGetter;?>();

        // sort by rank if available
        $rc = new \ReflectionClass(new <?php echo $className;?>());
        if ( $rc->hasMethod('getSortableRank')) {
            $pn->uasort(function($a, $b) {
                        return $a->getSortableRank() - $b->getSortableRank();
                    });
        }

        $pn = $pn->toKeyValue();
        return array_shift($pn);

    } else {
        return "-";
    }
}    