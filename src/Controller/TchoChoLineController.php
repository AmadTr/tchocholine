<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Category;
use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\CatPremierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
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
        CatPremierRepository $catPremierRepository,
        SessionInterface $session,
        CartService $cart
    ): Response {

        $session->remove('answer');
        $session->remove('category');

        if ($request->get('search')) {
            return $this->render('tcho_cho_line/index.html.twig', [
                'categories' => $categoryRepository->findAll(),
                'products' => $productRepository->findByExampleField([
                    $request->get('search'),
                ]),
                'catSups' => $catPremierRepository->findAll()
            ]);
        }

        $products = $productRepository->findAll();
        return $this->render('tcho_cho_line/index.html.twig', [
            'products' => $products,
            'categories' => $categoryRepository->findAll(),
            'catSups' => $catPremierRepository->findAll(),
            'items' => $cart->getCartDetails()
        ]);
    }

    /**
     * @Route("/indexByCategory/{cat}", name="app_product_indexByCategory", methods={"GET"})
     */
    public function indexByCategory(
        ProductRepository $productRepository,
        Category $cat,
        CategoryRepository $catRepo,
        CatPremierRepository $catPremierRepository,
        SessionInterface $session,
        CartService $cart
    ): Response {

        $session->set('category', $cat);

        return $this->render('tcho_cho_line/index.html.twig', [
            'products' => $productRepository->findBy([
            'category' => $cat->getId(),
            ]),
            'categories' => $catRepo->findAll(),
            'catSups' => $catPremierRepository->findAll(),
            'items' => $cart->getCartDetails()

        ]);
    }



    /**
     * @Route("TchoChoLine/{category}", name="app_find_category", methods={"GET"})
     */
    public function findByCategory(
        Category $category,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        CartService $cart

    ): Response {



        return $this->render('tcho_cho_line/index.html.twig', [
            'products' => $productRepository->findBy(['category' => $category]),
            'categories' => $categoryRepository->findAll(),
            'items' => $cart->getCartDetails()
        ]);
    }
    /**
     * @Route("/sort", name="app_sort", methods={"GET"})
     */
    public function sortByPrice(
        Request $request,
        CatPremierRepository $catPremierRepository,
        ProductRepository $productRepository,
        SessionInterface $session,
        CategoryRepository $categoryRepository,
        CartService $cart
    ) {
        // dd($request->get('answer'));
        // dd($request);
        $session->set('answer', $request->get('answer'));
        $cat = $session->get('category');


        return $this->render('tcho_cho_line/index.html.twig', [
            'products' => $productRepository->Sort([$session->get('category', $cat), $session->get('answer')]),
            'categories' => $categoryRepository->findAll(),
            'catSups' => $catPremierRepository->findAll(),
            'items' => $cart->getCartDetails()

        ]);
    }

    /**
     * @Route("/", name="app_indexCategories", methods={"GET"})
     */
    public function indexCategories(CategoryRepository $categoryRepository, CatPremierRepository $catPremierRepository): Response
    {

        return $this->render('base.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'catSups' => $catPremierRepository->findAll()

        ]);
    }

     /**
     * @Route("/acount", name="app_acount", methods={"GET"})
     */
    public function indexy(): Response
    {

        return $this->render('account/acount.html.twig', [
           

        ]);
    }

     /**
     * @Route("/payment", name="app_order_payement", methods={"GET"})
     * 
     */
    public function payment(): Response
    {

            return $this->render('order_line/payment.html.twig');
       
    }
}
