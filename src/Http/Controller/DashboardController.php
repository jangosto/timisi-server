<?php

namespace Infrastructure\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('dashboard', name: 'dashboard')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        return $this->render('manager/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}