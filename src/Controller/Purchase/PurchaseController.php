<?php

namespace App\Controller\Purchase;

use App\Repository\PurchaseRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PurchasePersisterService;
use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseController extends AbstractController
{

    protected $cartService;
    protected $em;
    protected $purchasePersister;
    protected $purchaseRepositrory;
    protected $stripeService;

    public function __construct(CartService $cartService, EntityManagerInterface $em, PurchasePersisterService $purchasePersister, PurchaseRepository $purchaseRepository, StripeService $stripeService)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->purchasePersister = $purchasePersister;
        $this->purchaseRepository = $purchaseRepository;
        $this->stripeService = $stripeService;
    }
}
