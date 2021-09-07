<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Event\PurchaseSuccessEvent;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PurchasePaymentSuccessController extends PurchaseController
{

    /** 
     * @Route("/purchase/done/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER") 
     */
    public function success($id, EventDispatcherInterface $dispatcher)
    {

        $purchase = $this->purchaseRepository->find($id);
        if (!$purchase || ($purchase && $purchase->getUser() !== $this->getUser()) || ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)) {
            $this->addFlash('warning', "La commande n'existe pas");
            return $this->redirectToroute('purchase_index');
        }

        $purchase->setStatus(Purchase::STATUS_PAID);
        $this->em->flush();

        $this->cartService->empty();

        $purchaseEvent = new PurchaseSuccessEvent($purchase);
        $dispatcher->dispatch($purchaseEvent, 'purchase.success');

        $this->addFlash('success', "La commande a été payée et confirmée !");
        return $this->redirectToRoute('purchase_index');
    }
}
