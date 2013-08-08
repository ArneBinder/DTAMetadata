<?php

namespace DTA\MetadataBundle\Model\Master;

use DTA\MetadataBundle\Model\Master\om\BaseDtaUser;

class DtaUser extends BaseDtaUser implements \Symfony\Component\Security\Core\User\UserInterface {

    protected $encoderFactory;

//    public function __construct($encoderFactory = null){
//        $this->encoderFactory = $encoderFactory;
//        parent::__construct();
//    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        $roles = array();

        if ($this->getAdmin()) {
            $roles[] = 'ROLE_ADMIN';
        } else {
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }

    public function adminToString() {
        return $this->getAdmin() ? "ja" : "nein";
    }

}
