<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BaseDtaUser;

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

    public function save(PropelPDO $con = NULL) {

        // must be the same parameters as in config.yml
        $algorithm = 'sha512';
        $iterations = 5000;
        $encode_as_base64 = true;
        // must be the same parameters as in config.yml

        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder($algorithm, $encode_as_base64, $iterations);

        // change password only if one has been given
        if ($this->getPassword() !== null) {
            $plainText = $this->getPassword();
            $salt = $this->getSalt();
            $encodedPassword = $encoder->encodePassword($plainText, $salt);
            $this->setPassword($encodedPassword);
        } else {
            // force propel to execute the query although it thinks it has the object already, 
            // because it's currently filled with the form data
            \Propel::disableInstancePooling();

            $uq = UserQuery::create()->findOneById($this->getId());
            if( null !== $uq ){
                $oldPassword = $uq->getPassword();
                $this->setPassword($oldPassword);
            }

            \Propel::enableInstancePooling();
        }
        parent::save();
    }

}
