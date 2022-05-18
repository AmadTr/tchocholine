<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="app_api")
     */
    public function index(CartService $carteService): Response
    {
        return $this->json([

            $carteService->getCartDetails(),
            $carteService->getCartTotal(),
            $carteService->getCartQty(),], 200, [], ['groups' => 'prods:read']);
    }
}
