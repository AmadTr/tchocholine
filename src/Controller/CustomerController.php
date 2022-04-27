<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\CustomerType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/customer")
*/
class CustomerController extends AbstractController
{
    /**
     * @Route("/", name="app_customer")
     */
    public function index(): Response
    {
        return $this->render('customer/deleteSuccess.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

     /**
     * @Route("/{id}", name="app_customer_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('customer/show.html.twig', [
            'user' => $user,
        ]);
    }


      /**
     * @Route("/profile/{id}", name="app_customer_indexProfile", methods={"GET"})
     */
    public function indexProfile(UserRepository $userRepository,UserInterface $user): Response
    {
        return $this->render('customer/index.html.twig', [
            'users' => $userRepository->findBy(['id'=>$user]),
        ]);
    }

     /**
     * @Route("/{id}/edit", name="app_customer_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(CustomerType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);
            return $this->redirectToRoute('app_customer_indexProfile',['id'=>$user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_customer_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_customer');
    }
}
