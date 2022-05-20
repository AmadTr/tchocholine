<?php

namespace App\Controller;

use App\Entity\Product;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class ApiProductController extends AbstractController
{
    /**
     * @Route("/product", name="api_product_index", methods={"GET"})
     */
    public function index(ProductRepository $prodRepo): Response
    {
        return $this->json($prodRepo->findAll(), 200, [], ['groups' => 'prods:read']);
    }
}
