
 
public function orderBy<?php echo $functionName;?>($direction){
    return <?php echo $orderEntity;?>Query::sqlSort($this->use<?php echo $orderEntity;?>Query(), $direction)->endUse();
}
