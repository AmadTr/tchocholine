<?php

namespace App\Controller;

use App\Entity\CatPremier;
use App\Form\CatPremierType;
use App\Repository\CatPremierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/cat/premier")
 */
class CatPremierController extends AbstractController
{
    
    /**
     * @Route("/", name="app_cat_premier_index", methods={"GET"})
     */
    public function index(CatPremierRepository $catPremierRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('cat_premier/index.html.twig', [
                'cat_premiers' => $catPremierRepository->findAll(),
            ]);
        }

        catch(AccessDeniedException $ex){
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/new", name="app_cat_premier_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CatPremierRepository $catPremierRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            $catPremier = new CatPremier();
            $form = $this->createForm(CatPremierType::class, $catPremier);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $catPremierRepository->add($catPremier);
                return $this->redirectToRoute('app_cat_premier_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('cat_premier/new.html.twig', [
                'cat_premier' => $catPremier,
                'form' => $form,
            ]);
        }
        catch(AccessDeniedException $ex){
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}", name="app_cat_premier_show", methods={"GET"})
     */
    public function show(CatPremier $catPremier): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('cat_premier/show.html.twig', [
                'cat_premier' => $catPremier,
            ]);
        }

        catch(AccessDeniedException $ex){
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}/edit", name="app_cat_premier_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CatPremier $catPremier, CatPremierRepository $catPremierRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            $form = $this->createForm(CatPremierType::class, $catPremier);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $catPremierRepository->add($catPremier);
                return $this->redirectToRoute('app_cat_premier_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('cat_premier/edit.html.twig', [
                'cat_premier' => $catPremier,
                'form' => $form,
            ]);
        }

        catch(AccessDeniedException $ex){
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}", name="app_cat_premier_delete", methods={"POST"})
     */
    public function delete(Request $request, CatPremier $catPremier, CatPremierRepository $catPremierRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            if ($this->isCsrfTokenValid('delete'.$catPremier->getId(), $request->request->get('_token'))) {
                $catPremierRepository->remove($catPremier);
            }

            return $this->redirectToRoute('app_cat_premier_index', [], Response::HTTP_SEE_OTHER);
        }
        
        catch(AccessDeniedException $ex){
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }
}


