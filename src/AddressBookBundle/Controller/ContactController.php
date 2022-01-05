<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\Contact;
use AddressBookBundle\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contact controller.
 *
 * @Route("/contacts")
 */
class ContactController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * ContactController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param EntityRepository       $contactRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EntityRepository $contactRepository
    )
    {
        $this->entityManager        = $entityManager;
        $this->contactRepository    = $contactRepository;
    }

    /**
     * Lists all Contact entities.
     *
     * @Route("/", name="contact_index")
     * @Method("GET")
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
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

        return $this->render('AddressBookBundle:Contact:index.html.twig', array(
            'contacts' => $result,
        ));
    }

    /**
     * Finds and displays a Contact entity.
     *
     * @Route("/contact/{id}", name="contact_show")
     * @Method("GET")
     * @param Contact $contact
     *
     * @return Response
     */
    public function showAction(Contact $contact)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $deleteForm = $this->createDeleteForm($contact);

        return $this->render('AddressBookBundle:Contact:show.html.twig', array(
            'contact' => $contact,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a new Contact entity.
     *
     * @Route("/new", name="contact_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $contact = new Contact();
        $form = $this->createForm('AddressBookBundle\Form\ContactType', $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setUser($user);
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $this->addFlash("success", sprintf('Contact <strong>%s %s</strong> created successfully.', $contact->getFirstname(), $contact->getLastname()));
            return $this->redirectToRoute('contact_index');
        }

        return $this->render('AddressBookBundle:Contact:new.html.twig', array(
            'contact' => $contact,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/edit/{id}", name="contact_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Contact $contact
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Contact $contact)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $editForm = $this->createForm('AddressBookBundle\Form\ContactType', $contact);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->addFlash("success", sprintf('Contact <strong>%s %s</strong> successfully edited.', $contact->getFirstname(), $contact->getLastname()));
            $this->entityManager->flush();

            return $this->redirectToRoute('contact_edit', array('id' => $contact->getId()));
        }

        return $this->render('AddressBookBundle:Contact:edit.html.twig', array(
            'contact' => $contact,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Contact entity.
     *
     * @Route("/delete/{id}", name="contact_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Contact $contact
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Contact $contact)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createDeleteForm($contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash("success", sprintf('Contact <strong>%s %s</strong> removed successfully.', $contact->getFirstname(), $contact->getLastname()));
            $this->entityManager->remove($contact);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('contact_index');
    }

    /**
     * @Route("/remove/{id}", name="contact_remove")
     * @param Contact $contact
     *
     * @return RedirectResponse
     */
    public function removeAction(Contact $contact){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->addFlash("success", sprintf('Contact <strong>%s %s</strong> removed successfully.', $contact->getFirstname(), $contact->getLastname()));
        $this->entityManager->remove($contact);
        $this->entityManager->flush();

        return $this->redirectToRoute('contact_index');
    }

    /**
     * Creates a form to delete a Contact entity.
     *
     * @param Contact $contact The Contact entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Contact $contact)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contact_delete', array('id' => $contact->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
