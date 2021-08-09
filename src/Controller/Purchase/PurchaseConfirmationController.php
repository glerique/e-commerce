<?php

namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Service\CartService;
use App\Form\CartConfirmationType;
use App\Service\PurchasePersisterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class PurchaseConfirmationController extends AbstractController
{
    protected $cartService;
    protected $em;
    protected $purchasePersister;

    public function __construct(CartService $cartService, EntityManagerInterface $em, PurchasePersisterService $purchasePersister)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->purchasePersister = $purchasePersister;
    }
    /**
     * @Route("/puchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message ="Vous devez vous identifier pour valider votre panier")
     */
    public function confirm(Request $request)
    {
        $form = $this->createForm(CartConfirmationType::class);


        //Analyser la request

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {

            $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');
            return $this->redirectToRoute('cart_show');
        }

        $user = $this->getUser();

        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash('warning', 'Vous ne pouvez pas valider un panier vide');
            return $this->redirectToRoute('cart_show');
        }
        /**  @var Purchase */
        $purchase = $form->getData();

        $this->purchasePersister->storePurchase($purchase);



        $this->cartService->empty();

        $this->addFlash('success', 'Votre commande a bien été enregistrée');
        return $this->redirectToRoute('purchase_index');
    }
}
