
/**
* embeds order function for get<?php echo $elementName;?> accessor from <?php echo $sourceClass;?>
*/
public function orderBy<?php echo $elementName;?>($direction){
    return $this->use<?php echo $sourceClass;?>Query()->orderBy<?php echo $elementName;?>($direction)->endUse();
}