<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BaseUser;

class User extends BaseUser implements \Symfony\Component\Security\Core\User\UserInterface
{
    public function eraseCredentials() {}

    public function getRoles() {
        $roles = array();
        
        if($this->getAdmin()){
            $roles[] = 'ROLE_ADMIN';
        } else {
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }
    
    public function adminToString(){
        return $this->getAdmin() ? "ja" : "nein";
    }
}
