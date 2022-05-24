<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\CustomerType;
use App\Repository\UserRepository;
use App\Form\Type\ChangePasswordType;
use App\Repository\CategoryRepository;
use App\Repository\CatPremierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{

    // private $user;

    // public function __construct(UserInterface $user)
    // {
    //     $this->user = $user;
    // }
    /**
     * @Route("/{id}", name="app_customer_show", methods={"GET"})
     */
    public function show(User $user, CatPremierRepository $catPremierRepository, CategoryRepository $categoryRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');

            return $this->render('customer/show.html.twig', [
                'user' => $user,
                'catSups' => $catPremierRepository->findAll(),
                'categories' => $categoryRepository->findAll()
            ]);
        } 
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }



    /**
     * @Route("/{id}/edit", name="app_customer_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository, CatPremierRepository $catPremierRepository, CategoryRepository $categoryRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');

            $form = $this->createForm(CustomerType::class, $user);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                // if (empty($user->getPassword())) {
                    
                //     $user->setPassword(
                //         $userPasswordHasher->hashPassword(
                //             $user,
                //             $form->get('password')->getData()
                //             )
                //         );
                // }
                $userRepository->add($user);
                return $this->redirectToRoute('app_customer_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('customer/edit.html.twig', [
                'user' => $user,
                'form' => $form,
                'catSups' => $catPremierRepository->findAll(),
                'categories' => $categoryRepository->findAll()
            ]);
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/changePassword/{id}", name="change_password", methods={"GET", "POST"})
     */
    public function changePassword(Request $request,UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, UserRepository $userRepository, CatPremierRepository $catPremierRepository, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class);
        // dd($request,$request->get('change_password')['newPassword']['first']);
        // dd($request->get('newPassword'));
        $form->handleRequest($request);
        // dd($user);
        
        if ($form->isSubmitted() && $form->isValid()) {
        
            $user->setPassword($hasher->hashPassword($user,$form->get('newPassword')->getData()));
            $entityManager->flush();
            
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('customer/changePassword.html.twig',[
            'user' => $user,
            'form' => $form->createView(),
            'catSups' => $catPremierRepository->findAll(),
            'categories' => $categoryRepository->findAll()


        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_customer_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');

            $session = new Session();
            $session->invalidate();
            if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
                $userRepository->remove($user);
            }

            return $this->redirectToRoute('app_logout');
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }
}
