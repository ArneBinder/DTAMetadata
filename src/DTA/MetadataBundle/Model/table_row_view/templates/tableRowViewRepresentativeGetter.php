/** 
 * Selects one of many related entities 
 */
 
<?php 
    $getterFunctionName = 'getRepresentative' . $className;
    $pluralizer = new \StandardEnglishPluralizer();
    $pluralizedClassname = $pluralizer->getPluralForm($className);
?>
public function <?php echo $getterFunctionName;?>(){
    
    $relatedEntities = $this->get<?php echo $pluralizedClassname?>();
    $relatedEntityArray = $relatedEntities->getArrayCopy();
    $relatedEntityCount = count($relatedEntityArray);
    if($relatedEntityCount > 0){
        $more = $relatedEntityCount-1;
        return $relatedEntityArray[0] . " [$more weitere]";
    } else {
        return "No entity associated.";
    }
    
}    


