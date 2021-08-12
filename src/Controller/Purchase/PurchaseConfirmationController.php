<?php

namespace App\Controller\Purchase;


use App\Entity\Purchase;
use App\Form\CartConfirmationType;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Purchase\PurchaseController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;




class PurchaseConfirmationController extends PurchaseController
{

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




        return $this->redirectToRoute('purchase_payment_form', [
            'id' => $purchase->getId()
        ]);
    }
}
