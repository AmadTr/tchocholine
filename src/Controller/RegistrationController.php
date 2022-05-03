<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\CategoryRepository;
use App\Repository\CatPremierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,CatPremierRepository $catPremierRepository, CategoryRepository $categoryRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'catSups' => $catPremierRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
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
