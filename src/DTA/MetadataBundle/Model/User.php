<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BaseUser;

class User extends BaseUser implements \Symfony\Component\Security\Core\User\UserInterface
{
    protected $encoderFactory;
    
//    public function __construct($encoderFactory = null){
//        $this->encoderFactory = $encoderFactory;
//        parent::__construct();
//    }
    
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
    
    public function save(PropelPDO $con = NULL){
        
        // must be given the same parameters as in config.yml
        $algorithm = 'sha512';
        $iterations = 5000;
        $encode_as_base64 = true;
        
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', $encode_as_base64, $iterations);

        $plainText = $this->getPassword();
        $salt = $this->getSalt();
        $encodedPassword = $encoder->encodePassword($plainText, $salt);
        
        $this->setPassword( $encodedPassword );
        parent::save();
    }
}
