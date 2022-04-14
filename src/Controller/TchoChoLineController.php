<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TchoChoLineController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        Request $request,
        ProductRepository $productRepository
    ): Response {
        
       
        if ($request->get('search')) {
            return $this->render('tcho_cho_line/index.html.twig', [
                'products' => $productRepository->findByExampleField([$request->get('search')])
            ]);
        }
        $products = $productRepository->findAll();
        return $this->render('tcho_cho_line/index.html.twig', [
            'products' => $products,
        ]);
    }
}
