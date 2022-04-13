<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
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
       CartService $cart
    ): Response {

        // dd($cartWithDate);
        return $this->render('cart/index.html.twig', [
            'items' => $cart->getCartDetails(),
            // 'nbreItems' => count($cartWithData),
            'total' => $cart->getCartTotal(),
            
        ]);
    }
    /**
     * @Route("/add/{id}",name="cart_add")
     */
    public function add($id, CartService $carteService)
    {
        $carteService->add($id);
        return $this->redirectToRoute('app_product_customer', [
            'controller_name' => 'CartController',
           
        ]);
    }
     /**
     * @Route("/addQty/{id}",name="cart_addQty")
     */
    public function addQtyItem($id, CartService $carteService)
    {
        $carteService->addQtyItem($id);
        return $this->redirectToRoute('app_cart', [
            'controller_name' => 'CartController',
           
        ]);
    }
    /**
     * @Route("/lessQty/{id}",name="cart_lessQty")
     */
    public function lessQtyItem($id, CartService $carteService)
    {
        $carteService->lessQtyItem($id);
        return $this->redirectToRoute('app_cart', [
            'controller_name' => 'CartController',
            // 'nbreItems' =>$cartService->getCartQty()
            // dd(count($cart))
            // 'nbreItems'=>$nbreitems
        ]);
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
