<?php

namespace App\Service;

use App\Lib\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    protected function saveCart(array $cart)
    {

        $this->session->set('cart', $cart);
    }

    public function empty()
    {
        $this->saveCart([]);
    }

    public function add(int $id)
    {
        $cart = $this->getCart();
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }
        $cart[$id]++;

        $this->saveCart($cart);
    }

    public function remove(int $id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);

        $this->saveCart($cart);
    }


    public function decrement(int $id)
    {
        $cart = $this->getCart();
        if (!array_key_exists($id, $cart)) {
            return;
        }
        if ($cart[$id] === 1) {

            $this->remove($id);
            return;
        }
        $cart[$id]--;
        $this->saveCart($cart);
    }

    public function increment(int $id)
    {
        $cart = $this->getCart();
        if (!array_key_exists($id, $cart)) {
            return;
        }
        $cart[$id]++;
        $this->saveCart($cart);
    }

    public function getTotal(): int
    {

        $total = 0;

        foreach ($this->session->get('cart', []) as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }

            $total += ($product->getPrice() * $quantity);
        }

        return $total;
    }
    /**
     * @return CartItem[]
     * 
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];


        foreach ($this->session->get('cart', []) as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }
            $detailedCart[] = new CartItem($product, $quantity);
        }
        return $detailedCart;
    }
}
