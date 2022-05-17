<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\CatPremierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/cart")
 *
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="app_cart")
     *
     */
    public function index(
        CartService $cart,
        CategoryRepository $categoryRepository,
        CatPremierRepository $catPremierRepository
    ): Response {

        return $this->render('cart/index.html.twig', [
            'items' => $cart->getCartDetails(),
            'total' => $cart->getCartTotal(),
            'categories' => $categoryRepository->findAll(),
            'catSups' => $catPremierRepository->findAll(),
        ]);
    }


    /**
     * @Route("/add/{id}",name="cart_add")
     */
    public function add($id, CartService $carteService)
    {
        $carteService->add($id);
        return $this->redirectToRoute('home', [
            'controller_name' => 'CartController',

        ]);
    }


    /**
     * @Route("/addQty/{id}",name="cart_addQty")
     */
    public function addQtyItem($id, CartService $carteService)
    {
        $carteService->addQtyItem($id);
        return $this->json([
            $carteService->getCartDetails()], 200, [], ['groups' => 'prods:read']);
    }


    /**
     * @Route("/lessQty/{id}",name="cart_lessQty")
     */
    public function lessQtyItem($id, CartService $carteService)
    {
        $carteService->lessQtyItem($id);
        return $this->json([
            $carteService->getCartDetails()], 200, [], ['groups' => 'prods:read']);
    }


    /**
     * @Route("/remove/{id}",name="cart_remove")
     */
    public function remove($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }


    /**
     * @Route("/delete",name="cart_delete")
     */
    public function deleteCart(SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if (!empty($cart)) {
            unset($cart);
        }
        $session->set('cart', []);

        return $this->redirectToRoute('app_cart');
    }
}
