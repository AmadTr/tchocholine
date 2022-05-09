<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('product/index.html.twig', [
                'products' => $productRepository->findAll(),
            ]);
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/new", name="app_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request,ProductRepository $productRepository): Response 
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            $product = new Product();
            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $image = $form->get('photo')->getData();

                $image->move(
                    $this->getParameter('images_directory'),
                    $image->getClientOriginalName()
                );

                $product->setPhoto($image->getClientOriginalName());

                $productRepository->add($product);
                return $this->redirectToRoute(
                    'app_product_index',
                    [],
                    Response::HTTP_SEE_OTHER
                );
            }

            return $this->renderForm('product/new.html.twig', [
                'product' => $product,
                'form' => $form,
            ]);
        } 
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/{id}", name="app_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('product/show.html.twig', [
                'product' => $product,
            ]);
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/{id}/edit", name="app_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,Product $product,ProductRepository $productRepository): Response 
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $image = $form->get('photo')->getData();

                $image->move(
                    $this->getParameter('images_directory'),
                    $image->getClientOriginalName()
                );

                $product->setPhoto($image->getClientOriginalName());

                $productRepository->add($product);
                return $this->redirectToRoute(
                    'app_product_index',
                    [],
                    Response::HTTP_SEE_OTHER
                );
            }

            return $this->renderForm('product/edit.html.twig', [
                'product' => $product,
                'form' => $form,
            ]);
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/{id}", name="app_product_delete", methods={"POST"})
     */
    public function delete(Request $request,Product $product,ProductRepository $productRepository): Response 
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            if (
                $this->isCsrfTokenValid(
                    'delete' . $product->getId(),
                    $request->request->get('_token')
                )
            ) {
                $productRepository->remove($product);
            }

            return $this->redirectToRoute(
                'app_product_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }
        catch (AccessDeniedException $ex) {
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
            return $this->redirectToRoute('home');
        }
    }
   
}
