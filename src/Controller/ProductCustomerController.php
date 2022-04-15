<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductCustomerController extends AbstractController
{
    /**
     * @Route("/products/customer", name="app_product_customer", methods={"GET"} )
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

     /**
     * @Route("/products/{id}", name="app_products_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product_customer/showProduct.html.twig', [
            'product' => $product,
        ]);
    }
}
