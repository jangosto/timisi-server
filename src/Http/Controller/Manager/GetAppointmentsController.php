<?php

namespace Infrastructure\Http\Controller\Manager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetAppointmentsController extends AbstractController
{
    #[Route("/appointments", name: "manager_get_appointments", methods: ["GET"])]
    public function getAppointments(Request $request): Response
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->redirectToRoute('manager_login');
        }

        // Datos de ejemplo que podrían ser obtenidos de una base de datos
        $appointments = [
            ['id' => 1, 'title' => 'Consulta médica', 'date' => '2025-01-20'],
            ['id' => 2, 'title' => 'Clase de pilates', 'date' => '2025-01-21'],
        ];

        // Renderiza la plantilla Twig y pasa los datos
        return $this->render('manager/get_appointments_calendat.html.twig', [
            'appointments' => $appointments,
        ]);
    }
}