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
     * @Route("/products/customer", name="app_product_customer" )
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product_customer/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }
}
