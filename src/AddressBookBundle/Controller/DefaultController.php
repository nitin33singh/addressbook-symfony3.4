<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Repository\ContactRepository;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * ContactController constructor.
     *
     * @param EntityRepository $contactRepository
     */
    public function __construct(
        EntityRepository $contactRepository
    )
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @Route("/")
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();

            $contacts = $this->contactRepository->findBy(['user' => $userId]);

            if (!$contacts) {
                $this->addFlash("warning", 'There is no contacts in your list. <a href="\contact\new" class="alert-link">Add new contact</a>');
                return $this->redirectToRoute('homepage');
            }

            /**
             * @var $paginator Paginator
             */
            $paginator = $this->get('knp_paginator');
            $result = $paginator->paginate(
                $contacts,
                $request->query->getInt('page', 1),
                $request->query->getInt('limit', 5)
            );

        } else {
            $result = null;
        }

        return $this->render('AddressBookBundle:Default:index.html.twig', array(
            'contacts' => $result,
        ));
    }
}
