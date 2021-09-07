<?php

namespace App\Service;

use DateTime;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersisterService
{
    protected $security;
    protected $cartService;
    protected $em;

    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $em)
    {

        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;
    }




    public function storePurchase(Purchase $purchase)
    {
        $user = $this->security->getUser();
        $purchase->setUser($user);


        $this->em->persist($purchase);


        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {

            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getPrice())
                ->setQuantity($cartItem->quantity)
                ->setTotal($cartItem->getTotal());

            $this->em->persist($purchaseItem);
        }

        $this->em->flush();
    }
}
