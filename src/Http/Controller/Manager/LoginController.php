<?php

namespace Infrastructure\Http\Controller\Manager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route("/login", name: "manager_login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('manager_dashboard'); // Evitar redirección infinita
        }

        return $this->render('manager/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/manager/logout', name: 'manager_logout')]
    public function logout(): void
    {
        // Symfony maneja la salida, este método nunca se ejecuta
        throw new \LogicException('Logout should be handled by Symfony.');
    }
}