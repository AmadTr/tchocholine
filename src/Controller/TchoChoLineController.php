<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\CatPremierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TchoChoLineController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        Request $request,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        CatPremierRepository $catPremierRepository
    ): Response {
        
        if ($request->get('search')) {
            return $this->render('tcho_cho_line/index.html.twig', [
                'categories' => $categoryRepository->findAll(),
                'products' => $productRepository->findByExampleField([
                    $request->get('search'),
                ]),
            ]);
        }
        $products = $productRepository->findAll();
        return $this->render('tcho_cho_line/index.html.twig', [
            'products' => $products,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/indexByCategory/{cat}", name="app_product_indexByCategory", methods={"GET"})
     */
    public function indexByCategory(
        ProductRepository $productRepository,
        Category $cat,
        CategoryRepository $catRepo
        ,
        SessionInterface $session

    ): Response {
        // dd($cat);
        $session->set('category', $cat);
        
        return $this->render('tcho_cho_line/index.html.twig', [
            'products' => $productRepository->findBy([
                'category' => $cat->getId(),
            ]),
            'categories' => $catRepo->findAll(),
        ]);
    }
    /**
     * @Route("TchoChoLine/{category}", name="app_find_category", methods={"GET"})
     */
    public function findByCategory(
        Category $category,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
        
    ): Response {

        

        return $this->render('tcho_cho_line/index.html.twig', [
            'products' => $productRepository->findBy(['category' => $category]),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
    /**
     * @Route("/sort", name="app_sort", methods={"GET"})
     */
    public function sortByPrice(Request $request, ProductRepository $productRepository, SessionInterface $session, CategoryRepository $categoryRepository){
        // dd($request->get('answer'));
        $session->set('answer', $request->get('answer'));
        $cat = $session->get('category');
        

        return $this->render('tcho_cho_line/index.html.twig', [
            'products' => $productRepository->Sort([$session->get('category', $cat),$session->get('answer')]),
            'categories' => $categoryRepository->findAll(),
        ]);

           
     

    }
}
