<?php

namespace App\Controller\Client;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NotificationsController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications')]
    public function index(NotificationRepository $notificationRepository): Response
    {
        $notifications = $notificationRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('notifications/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }
}
