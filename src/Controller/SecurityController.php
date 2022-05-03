<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CatPremierRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils,CatPremierRepository $catPremierRepository, CategoryRepository $categoryRepository): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
            
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,
        'catSups' => $catPremierRepository->findAll(),
        'categories' => $categoryRepository->findAll()]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

      /**
     * @Route("/", name="app_indexCategories", methods={"GET"})
     */
    public function indexCategories(CategoryRepository $categoryRepository,CatPremierRepository $catPremierRepository): Response
    {

            return $this->render('base.html.twig', [
                'categories' => $categoryRepository->findAll(),
                'catSups' => $catPremierRepository->findAll()

            ]);

        
    }
}
