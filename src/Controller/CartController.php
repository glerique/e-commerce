<?php

namespace App\Controller;

use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CartController extends AbstractController
{

    protected $cartService;
    protected $productRepository;

    public function __construct(CartService $cartService, ProductRepository $productRepository)
    {
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id): Response
    {

        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }
        $this->cartService->add($id);
        $this->addFlash('success', "Le produit a bien été ajouté");
        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }



    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(): Response
    {

        $form = $this->createForm(CartConfirmationType::class);
        $detailedCart = $this->cartService->getDetailedCartItems();

        $total  = $this->cartService->getTotal();


        return $this->render("/cart/index.html.twig", [
            'items' => $detailedCart,
            'total' => $total,
            'confirmationForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/cart/delete{id}", name="cart_delete", requirements={"id":"\d+"})
     */
    public function delete($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas");
        }

        $this->cartService->remove($id);
        $this->addFlash('success', "Le produit a bien été supprimé du panier");
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/carte/decrement/{id}", name="cart_decrement", requirements={"id":"\d+"})
     */
    public function decrement($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas");
        }

        $this->cartService->decrement($id);
        $this->addFlash('success', "La quantité du produit a bien été diminué");
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/carte/increment/{id}", name="cart_increment", requirements={"id":"\d+"})
     */
    public function increment($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas");
        }

        $this->cartService->increment($id);
        $this->addFlash('success', "La quantité du produit a bien été augmenté");
        return $this->redirectToRoute('cart_show');
    }
}
