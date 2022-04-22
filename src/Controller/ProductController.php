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
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();

            foreach ($images as $image) {
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $img = new Images();
                $img->setLink($fichier);
                $product->addImage($img);

            }

            $productRepository->add($product);
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();

            foreach ($images as $image) {
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $img = new Images();
                $img->setLink($fichier);
                $product->addImage($img);

            }

            $productRepository->add($product);
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }



    /**
    * @Route("/delete/image/{id}", name="prod_delete_image", methods={"DELETE"})
    */
    public function deleteImage(Images $image, Request $request){
    

        $data = json_decode($request->getProduct(), true);
        // dd($data);
        
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            $name = $image->getName();
            unlink($this->getParameter('images_directory').'/'.$name);

            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($image);
            $em->flush();

            return new JsonResponse(['success' => 1]);
        }
        else{
            return new JsonResponse(['error' => 'token invalide'], 400);
        }

    }

}
