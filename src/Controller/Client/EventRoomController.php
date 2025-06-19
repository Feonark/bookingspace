<?php

namespace App\Controller\Client;

use App\Entity\Booking;
use App\Entity\EventRoom;
use App\Enum\BookingStatus;
use App\Form\BookingForm;
use App\Repository\EventRoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/eventrooms')]
final class EventRoomController extends AbstractController
{
    public function __construct(
        private EventRoomRepository    $errepo,
        private EntityManagerInterface $em
    )
    {
    }

    #[Route('/', name: 'eventrooms_list')]
    public function index(): Response
    {
        return $this->render('eventroom/eventrooms.html.twig', [
            'controller_name' => 'EventRoomController',
        ]);
    }

    #[Route('/fast_book/{eventRoom}/{dateStart}/{dateEnd}', name: 'fast_book_eventroom')]
    public function fastBook(EventRoom $eventRoom, string $dateStart, string $dateEnd): Response
    {
        $booking = new Booking();

        $booking->setEventRoom($eventRoom)
            ->setDateStart(new \DateTime($dateStart))
            ->setDateEnd(new \DateTime($dateEnd))
            ->setAppUser($this->getUser())
        ->setBookingStatus(BookingStatus::PENDING);


        $this->em->persist($booking);
        $this->em->flush();
        $this->addFlash('success', 'Votre réservation a bien été enregistrée.');
        return $this->redirectToRoute('eventrooms_list');
    }

    #[Route('/{eventRoom}', name: 'eventroom')]
    public function view(EventRoom $eventRoom, Request $request): Response
    {

        $booking = new Booking();
        $bookingForm = $this->createForm(BookingForm::class, $booking);
        $bookingForm->handleRequest($request);

        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {

            // Vérifie si une des dates est dans le passé
            $now = new \DateTimeImmutable('today'); // ignore l'heure

            if ($booking->getDateStart() < $now || $booking->getDateEnd() < $now) {
                $this->addFlash('error', 'Les dates doivent être postérieures à aujourd’hui.');
                return $this->render('booking/book.html.twig', [
                    'bookingForm' => $bookingForm->createView(),
                    'eventRoom' => $eventRoom
                ]);
            }

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
                ->select('count(b.id)')
                ->where('b.eventRoom = :room')
                ->andWhere('b.bookingStatus != :cancelled') // si tu gères les annulations
                ->andWhere('b.dateStart < :end AND b.dateEnd > :start')
                ->setParameter('room', $eventRoom)
                ->setParameter('start', $booking->getDateStart())
                ->setParameter('end', $booking->getDateEnd())
                ->setParameter('cancelled', BookingStatus::CANCELLED) // sinon enlève cette ligne
                ->getQuery()
                ->getSingleScalarResult();

            if ($existingBookings > 0) {
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
            return $this->redirectToRoute('eventrooms_list');
        }

        return $this->render('eventroom/view.html.twig', [
            'bookingForm' => $bookingForm->createView(),
            'eventRoom' => $eventRoom
        ]);
    }
}
