<?php

namespace App\Controller;

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

        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {

            // Vérifie si dateEnd est avant dateStart
            if ($booking->getDateEnd() <= $booking->getDateStart()) {
                $this->addFlash('error', 'La date de fin doit être postérieure à la date de début.');
                return $this->render('booking/book.html.twig', [
                    'bookingForm' => $bookingForm->createView(),
                    'eventRoom' => $eventRoom
                ]);
            }

            // Vérification du chevauchement
            $existingBookings = $this->em->getRepository(Booking::class)->createQueryBuilder('b')
                ->where('b.eventRoom = :room')
                ->andWhere('b.bookingStatus != :cancelled') // si tu gères les annulations
                ->andWhere('b.dateStart < :end AND b.dateEnd > :start')
                ->setParameter('room', $eventRoom)
                ->setParameter('start', $booking->getDateStart())
                ->setParameter('end', $booking->getDateEnd())
                ->setParameter('cancelled', BookingStatus::CANCELLED) // sinon enlève cette ligne
                ->getQuery()
                ->getResult();

            if (count($existingBookings) > 0) {
                $this->addFlash('error', 'Ce créneau est déjà réservé pour cette salle.');
                return $this->render('booking/book.html.twig', [
                    'bookingForm' => $bookingForm->createView(),
                    'eventRoom' => $eventRoom
                ]);
            }

            // Si tout est OK, on enregistre
            $booking->setAppUser($this->getUser());
            $booking->setEventRoom($eventRoom);
            $booking->setBookingStatus(BookingStatus::PENDING);

            $this->em->persist($booking);
            $this->em->flush();

            $this->addFlash('success', 'Votre réservation a bien été enregistrée.');
            return $this->redirectToRoute('eventrooms');
        }

        return $this->render('booking/book.html.twig', [
            'bookingForm' => $bookingForm->createView(),
            'eventRoom' => $eventRoom
        ]);
    }
}
