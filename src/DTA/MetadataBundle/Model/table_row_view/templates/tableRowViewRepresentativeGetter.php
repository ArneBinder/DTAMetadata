/** 
 * Selects one of many related entities 
 */
 
<?php 
    $representativeFunctionName = 'getRepresentative' . $className;
    $countFunctionName = 'getRepresentative' . $className . 'Count';
    $pluralizer = new \StandardEnglishPluralizer();
    $pluralizedClassname = $pluralizer->getPluralForm($className);
?>
public function <?php echo $representativeFunctionName;?>(){
    
    $relatedEntities = $this->get<?php echo $pluralizedClassname?>();
    $relatedEntityArray = $relatedEntities->getArrayCopy();
    $relatedEntityCount = count($relatedEntityArray);

    if($relatedEntityCount == 1){
        return $relatedEntityArray[0];
    } elseif($relatedEntityCount > 1){
        return $relatedEntityArray[0];
    } else {
        return "-";
    }
}    

public function <?php echo $countFunctionName;?>(){
    
    $relatedEntities = $this->get<?php echo $pluralizedClassname?>();
    $relatedEntityArray = $relatedEntities->getArrayCopy();
    $relatedEntityCount = count($relatedEntityArray);

    return $relatedEntityCount;
}    

