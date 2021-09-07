<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\ProductViewEvent;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewEmailSubscriber implements EventSubscriberInterface
{

    protected $logger;
    protected $mailer;


    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }


    public static function getSubscribedEvents()
    {
        return [
            'product.show' => 'sendEmail'
        ];
    }

    public function sendEmail(ProductViewEvent $productViewEvent)
    {
        /*
        $email = new Email();
        $email->from(new Address("contact@site.com", "Information de la boutique"))
            ->to("admin@site.com")
            ->subject("Consultation du produit " . $productViewEvent->getProduct()->getId())
            ->text("Un visiteur a consulté le produit n° " . $productViewEvent->getProduct()->getId());

        $this->mailer->send($email);
        */

        $this->logger->info("Email envoyé pour le produit n° " . $productViewEvent->getProduct()->getId());
    }
}
