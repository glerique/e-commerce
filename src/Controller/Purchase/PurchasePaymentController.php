<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class PurchasePaymentController extends PurchaseController
{




    /**
     * @Route("/purchase/pay/{id}", name="purchase_payment_form")
     * @IsGranted("ROLE_USER")
     */
    public function showCardForm($id)
    {

        $purchase = $this->purchaseRepository->find($id);


        if (!$purchase || ($purchase && $purchase->getUser() !== $this->getUser()) || ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)) {

            $this->redirectToRoute('cart_show');
        }

        $intent = $this->stripeService->getPaymentIntent($purchase);
        return $this->render('purchase/payment.html.twig', [
            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase,
            'stripePublicKey' => $this->stripeService->getPublicKey()
        ]);
    }
}
