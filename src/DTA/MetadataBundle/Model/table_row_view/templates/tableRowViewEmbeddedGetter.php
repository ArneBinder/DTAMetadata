/** 
 * Cascades the get to a related entity (possibly recursively)
 */
 
public function <?php echo $functionName;?>(){
    
    $relatedEntity = $this->get<?php echo $relatedEntity; ?>();
    return $relatedEntity->getAttributeByTableViewColumName("<?php echo $caption; ?>");
    
}    