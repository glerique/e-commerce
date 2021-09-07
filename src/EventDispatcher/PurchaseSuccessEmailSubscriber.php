<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Event\PurchaseSuccessEvent;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    protected $security;
    protected $mailer;



    public function __construct(Security $security, MailerInterface $mailer)
    {
        $this->security = $security;
        $this->mailer = $mailer;
    }


    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        /**
         * @var User 
         */
        $currentUser = $this->security->getUser();

        $purchase = $purchaseSuccessEvent->getPurchase();

        $email = new TemplatedEmail();
        $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
            ->from("contact@site.com")
            ->subject("Confirmation commande N° " . $purchaseSuccessEvent->getPurchase()->getId())
            ->htmlTemplate('email/purchase_success.html.twig')
            ->context([
                'purchase' => $purchase,
                'user' => $currentUser
            ]);

        $this->mailer->send($email);
    }
}
