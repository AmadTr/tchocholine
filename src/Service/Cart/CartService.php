<?php

namespace App\Service\Cart;

use App\Repository\ProductRepository;
use SessionIdInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepository;
    public function __construct(
        SessionInterface $session,
        ProductRepository $productRepository
    ) {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function clearCart()
    {

        $cart = $this->session->get('cart', []);

        if (!empty($cart)) {
            unset($cart);
        }
        $this->session->set('cart', []);
    }

    public function add(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$id]) && $cart[$id] >= $this->productRepository->Find($id)->getStock()) {
            ///écrire message d'erreur pour stipuler stock max 
        } else
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }
    public function addQtyItem(int $id)
    {
        // dd($this->productRepository->Find($id)->GetStock());

        $cart = $this->session->get('cart', []);
        if ($cart[$id] >= $this->productRepository->Find($id)->getStock()) {
            ///écrire message d'erreur pour stipuler stock max 
        } else if (!empty($cart[$id])) {
            $cart[$id]++;
        }
        $this->session->set('cart', $cart);
    }
    public function lessQtyItem(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id]) && $cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }

    public function getCartDetails(): array
    {
        $cart = $this->session->get('cart', []);
        $cartWithData = [];
        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity,
            ];
        }
        return $cartWithData;
    }

    public function getCartTotal(): float
    {
        $total = 0;
        foreach ($this->getCartDetails() as $item) {
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }
        return $total;
    }

    public function getCartQty()
    {
        $totalQty = 0;
        $cart = $this->session->get('cart', []);
        foreach ($cart as $id => $qty) {
            $totalQty += $qty;
            # code...
        }
        return $totalQty;
    }
}
