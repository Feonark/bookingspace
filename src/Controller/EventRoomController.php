<?php

namespace App\Controller;

use App\Repository\EventRoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/eventroom')]
final class EventRoomController extends AbstractController
{
    public function __construct(
        private EventRoomRepository $errepo,
        private EntityManagerInterface $em
    ) {}

    #[Route('s', name: 'eventrooms')]
    public function index(): Response
    {
        return $this->render('eventroom/eventrooms.html.twig', [
            'controller_name' => 'EventRoomController',
        ]);
    }
}
