<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {

    #[Route("/login", name: "login")]
    public function Login(AuthenticationUtils $authenticationUtils) {

        $username = $authenticationUtils->getLastUsername();

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig',[
            'last_username'=> $username,
            'error' => $error
        ]);
    }
}