<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Booking;
use App\Form\BookingForm;
use App\Enum\BookingStatus;
use App\Repository\EventRoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/{id}', name: 'eventroom')]
    public function view(string $id): Response
    {
        return $this->render('eventroom/view.html.twig', [
            'eventRoom' => $this->errepo->findOneById($id)
        ]);
    }

    #[Route('/{id}/book', name: 'eventroom_book', methods: ['GET', 'POST'])]
    public function book(string $id, Request $request): Response
    {
        $eventRoom = $this->errepo->findOneById($id);
        $booking = new Booking();
        $bookingForm = $this->createForm(BookingForm::class, $booking);
        $bookingForm->handleRequest($request);

        // Traitement du formulaire 
        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {

            $booking->setAppUser($this->getUser()); // Récupération de l'utilisateur
            $booking->setEventRoom($eventRoom); // Récupération de l'eventRoom
            $booking->setBookingStatus(BookingStatus::PENDING); // Setting du BookingStatus

            $this->em->persist($booking); // Enregistrement du booking (query SQL)
            $this->em->flush($booking); // Exécution de l'erregistrement en BDD

            return $this->redirectToRoute('eventrooms'); // Redirection vers les eventrooms
        }

        return $this->render('booking/book.html.twig', [
            'bookingForm' => $bookingForm->createView() // ✅ converti en vue pour Twig
        ]);
    }
}
