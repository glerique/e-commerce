<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername(Security::LAST_USERNAME)]);
        $error = $utils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
            'error' => $error !== null
        ]);
    }


    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }
}
