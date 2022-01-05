<?php

namespace AddressBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $errors = $authenticationUtils->getLastAuthenticationError();
        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('AddressBookBundle:Login:login.html.twig', array(
            'errors' => $errors,
            'username' => $lastUserName,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }

}