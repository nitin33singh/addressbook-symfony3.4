<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\User;
use AddressBookBundle\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends Controller
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * RegisterController constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface       $entityManager
     */
    public function __construct(
        UserPasswordEncoderInterface    $encoder,
        EntityManagerInterface          $entityManager
    )
    {
        $this->encoder          = $encoder;
        $this->entityManager    = $entityManager;
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash("success", "User registered successfully.  ". $user->getUsername()." (".$user->getEmail().")");
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('AddressBookBundle:Register:register.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
