<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller\Manager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/manager/dashboard/', name: 'manager_dashboard')]
    public function index(): Response
    {
        return $this->render('manager/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
