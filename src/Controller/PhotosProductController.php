<?php

namespace App\Controller;

use App\Entity\PhotosProduct;
use App\Form\PhotosProductType;
use App\Repository\PhotosProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN", statusCode=404, message="Page introuvable")
 * @Route("/photos/product")
 */
class PhotosProductController extends AbstractController
{
    /**
     * @Route("/", name="app_photos_product_index", methods={"GET"})
     */
    public function index(
        PhotosProductRepository $photosProductRepository
    ): Response {
        return $this->render('photos_product/index.html.twig', [
            'photos_products' => $photosProductRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_photos_product_new", methods={"GET", "POST"})
     */
    public function new(
        Request $request,
        PhotosProductRepository $photosProductRepository
    ): Response {
        $photosProduct = new PhotosProduct();
        $form = $this->createForm(PhotosProductType::class, $photosProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            $images = $form->get('link')->getData();
            $product = $form->get('product')->getData();
            // dd($images);

            foreach ($images as $img) {
                $fichier = $img->getClientOriginalName();

                $img->move($this->getParameter('images_directory'), $fichier);

                $photosProduct = new PhotosProduct();

                $photosProduct->setLink($fichier);
                $photosProduct->setProduct($product);

                $photosProductRepository->add($photosProduct);
            }
            return $this->redirectToRoute(
                'app_photos_product_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('photos_product/new.html.twig', [
            'photos_product' => $photosProduct,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_photos_product_show", methods={"GET"})
     */
    public function show(PhotosProduct $photosProduct): Response
    {
        return $this->render('photos_product/show.html.twig', [
            'photos_product' => $photosProduct,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_photos_product_edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        PhotosProduct $photosProduct,
        PhotosProductRepository $photosProductRepository
    ): Response {
        $form = $this->createForm(PhotosProductType::class, $photosProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('link')->getData();
            $product = $form->get('product')->getData();
            // dd($images);

            foreach ($images as $img) {
                $fichier = $img->getClientOriginalName();

                $img->move($this->getParameter('images_directory'), $fichier);

                $photosProduct = new PhotosProduct();

                $photosProduct->setLink($fichier);
                $photosProduct->setProduct($product);

                $photosProductRepository->add($photosProduct);
            }
            return $this->redirectToRoute(
                'app_photos_product_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('photos_product/edit.html.twig', [
            'photos_product' => $photosProduct,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_photos_product_delete", methods={"POST"})
     */
    public function delete(
        Request $request,
        PhotosProduct $photosProduct,
        PhotosProductRepository $photosProductRepository
    ): Response {
        if (
            $this->isCsrfTokenValid(
                'delete' . $photosProduct->getId(),
                $request->request->get('_token')
            )
        ) {
            $photosProductRepository->remove($photosProduct);
        }

        return $this->redirectToRoute(
            'app_photos_product_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }
}
