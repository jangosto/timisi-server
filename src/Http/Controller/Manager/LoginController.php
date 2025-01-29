<?php

namespace Infrastructure\Http\Controller\Manager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route("/login", name: "manager_login", methods: ["GET"])]
    public function login(Request $request): Response
    {
        return $this->render('manager/login.html.twig');
    }
}