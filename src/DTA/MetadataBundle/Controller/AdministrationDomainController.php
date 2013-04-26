<?php

namespace DTA\MetadataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Symfony\Component\Security\Core\SecurityContext;

class AdministrationDomainController extends DTABaseController {

    /** @inheritdoc */
    public static $domainKey = "AdministrationDomain";

    /** @inheritdoc */
    public $domainMenu = array(
        array("caption" => "Benutzer", 'modelClass' => 'User'),
    );

    /**
     * Displays the login form for the entire application.
     * @Route("/Anmeldung", name="login")
     */
    public function loginFormAction() {

        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
                    'DTAMetadataBundle::login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                    )
        );
    }

    /**
     * 
     * @return type
     * @Route("/daten/", name="administrationDomain")
     */
    public function indexAction() {

        return $this->renderControllerSpecificAction('DTAMetadataBundle:AdministrationDomain:index.html.twig', array(
                    'hash' => 200
                ));
    }

}
