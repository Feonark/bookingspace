<?php

namespace App\Controller\Client;

use App\Entity\Booking;
use App\Entity\EventRoom;
use App\Enum\BookingStatus;
use App\Form\BookingForm;
use App\Repository\EventRoomRepository;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/eventroom')]
final class EventRoomController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    #[Route('s', name: 'eventrooms_list')]
    public function index(): Response
    {
        return $this->render('eventroom/eventrooms.html.twig', [
            'controller_name' => 'EventRoomController',
        ]);
    }

    #[Route('/{eventRoom}', name: 'eventroom')]
    public function view(EventRoom $eventRoom, Request $request): Response
    {
        $booking = new Booking();
        $bookingForm = $this->createForm(BookingForm::class, $booking);
        $bookingForm->handleRequest($request);

        if ($bookingForm->isSubmitted()) {
            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }

            if ($this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('info', 'Les administrateurs ne peuvent pas réserver de salle.');
                return $this->redirectToRoute('eventroom', ['eventRoom' => $eventRoom->getId()]);
            }

            if (!$bookingForm->isValid()) {
                $formErrors = $bookingForm->getErrors(true);
                $messages = [];
                foreach ($formErrors as $formError) {
                    $messages[] = $formError->getMessage() . ".\n";
                }
                $this->addFlash('error', implode(' ', $messages));
                return $this->redirectToRoute('eventroom', ['eventRoom' => $eventRoom->getId()]);
            }
            $existingBookings = $this->em->getRepository(Booking::class)->createQueryBuilder('b')
                ->select('count(b.id)')
                ->where('b.eventRoom = :room')
                ->andWhere('b.dateStart < :end AND b.dateEnd > :start')
                ->setParameter('room', $eventRoom)
                ->setParameter('start', $booking->getDateStart())
                ->setParameter('end', $booking->getDateEnd())
                ->getQuery()
                ->getSingleScalarResult();

            if ($existingBookings > 0) {
                $this->addFlash('error', 'Ce créneau est déjà réservé pour cette salle.');
                return $this->redirectToRoute('eventroom', ['eventRoom' => $eventRoom->getId()]);
            }

            $booking->setAppUser($this->getUser());
            $booking->setEventRoom($eventRoom);
            $booking->setBookingStatus(BookingStatus::PENDING);

            $this->em->persist($booking);
            $this->em->flush();

            $this->addFlash('success', 'Votre réservation a bien été enregistrée.');
            return $this->redirectToRoute('bookings');
        }

        return $this->render('eventroom/view.html.twig', [
            'bookingForm' => $bookingForm->createView(),
            'eventRoom' => $eventRoom
        ]);
    }

    #[Route('/{eventRoom}/bookings', name: 'eventroom_bookings', methods: ['GET'])]
    public function bookings(EventRoom $eventRoom, BookingRepository $bookingRepository): JsonResponse
    {
        $bookings = $bookingRepository->findBy(['eventRoom' => $eventRoom]);

        $events = [];
        foreach ($bookings as $booking) {
            $endDate = (clone $booking->getDateEnd())->modify('+1 day');
            $events[] = [
                'id' => $booking->getId(),
                'title' => 'Réservation',
                'start' => $booking->getDateStart()->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ];
        }

        return $this->json($events);
    }
}
