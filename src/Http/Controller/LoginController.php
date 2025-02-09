<?php

namespace Infrastructure\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route("/login", name: "login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        #if ($this->getUser() && in_array('ROLE_MANAGER', $this->getUser()->getRoles(), true)) {
        #    return $this->redirectToRoute('manager_dashboard'); // Evitar redirección infinita
        #}

        return $this->render('login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

#    #[Route("/login_check", name: "manager_login_check", methods: ["POST"])]
#    public function loginCheck(): void
#    {
#        throw new \LogicException('Este método nunca debería ejecutarse.');
#    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        // Symfony maneja la salida, este método nunca se ejecuta
        throw new \LogicException('Logout should be handled by Symfony.');
    }
}