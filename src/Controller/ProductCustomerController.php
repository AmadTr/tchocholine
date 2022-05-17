<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\CatPremierRepository;
use App\Repository\PhotosProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductCustomerController extends AbstractController
{
    /**
     * @Route("/products/customer", name="app_product_customer", methods={"GET"} )
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product_customer/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    /**
     * @Route("/products/{id}", name="app_products_show", methods={"GET"})
     */
    public function show(Product $product, CartService $cart,CategoryRepository $categoryRepository, CatPremierRepository $catPremierRepository, PhotosProductRepository $photosProds): Response
    {
        return $this->render('product_customer/showProduct.html.twig', [
            'product' => $product,
            'items' => $cart->getCartDetails(),
            'categories' => $categoryRepository->findAll(),
            'catSups' => $catPremierRepository->findAll(),
            'photosProds' => $photosProds->findBy(['product' => $product])
        ]);
    }
}
