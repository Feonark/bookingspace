<?php

namespace App\Controller\Client;

use App\Entity\Booking;
use App\Entity\EventRoom;
use App\Form\BookingForm;
use App\Enum\BookingStatus;
use App\Repository\BookingRepository;
use App\Repository\EventRoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/booking')]
final class BookingController extends AbstractController
{

    public function __construct(
        private BookingRepository $bookingRepository,
        private EventRoomRepository $errepo,
        private EntityManagerInterface $em
    ) {}

    #[Route('s', name: 'bookings')]
    public function list(): Response
    {
        $user = $this->getUser();
        $bookings = $this->bookingRepository->getUserBookings($user);
        return $this->render(
            'booking/list.html.twig',
            ['bookings' => $bookings]
        );
    }

    #[Route('/{booking}/edit', name: 'booking_edit', methods: ['GET', 'POST'])]
    public function edit(Booking $booking, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($booking->getAppUser() !== $user) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas modifier cette réservation.");
        }

        $eventRoom = $booking->getEventRoom();
        $form = $this->createForm(BookingForm::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $formErrors = $form->getErrors(true);
                $messages = [];
                foreach ($formErrors as $formError) {
                    $messages[] = $formError->getMessage();
                }
                $this->addFlash('error', implode(' ', $messages));
                return $this->redirectToRoute('booking_edit', ['id' => $booking->getId()]);
            }

            $now = new \DateTimeImmutable('today');
            if ($booking->getDateStart() < $now || $booking->getDateEnd() < $now) {
                $this->addFlash('error', 'Les dates doivent être postérieures à aujourd’hui.');
                return $this->redirectToRoute('booking_edit', ['id' => $booking->getId()]);
            }

            if ($booking->getDateEnd() <= $booking->getDateStart()) {
                $this->addFlash('error', 'La date de fin doit être postérieure à la date de début.');
                return $this->redirectToRoute('booking_edit', ['id' => $booking->getId()]);
            }

            // Vérifie s'il y a un chevauchement avec d'autres réservations
            $existingBookings = $em->getRepository(Booking::class)->createQueryBuilder('b')
                ->select('count(b.id)')
                ->where('b.eventRoom = :room')
                ->andWhere('b.bookingStatus != :cancelled')
                ->andWhere('b.id != :currentId')
                ->andWhere('b.dateStart <= :end AND b.dateEnd >= :start')
                ->setParameter('room', $eventRoom)
                ->setParameter('cancelled', BookingStatus::CANCELLED)
                ->setParameter('currentId', $booking->getId())
                ->setParameter('start', $booking->getDateStart())
                ->setParameter('end', $booking->getDateEnd())
                ->getQuery()
                ->getSingleScalarResult();

            if ($existingBookings > 0) {
                $this->addFlash('error', 'Ce créneau est déjà réservé pour cette salle.');
                return $this->redirectToRoute('booking_edit', ['id' => $booking->getId()]);
            }

            $em->flush();

            $this->addFlash('success', 'Votre réservation a bien été mise à jour.');
            return $this->redirectToRoute('bookings');
        }

        return $this->render('booking/edit.html.twig', [
            'bookingForm' => $form->createView(),
            'eventRoom' => $eventRoom,
        ]);
    }

    #[Route('/{booking}/cancel', name: 'booking_cancel', methods: ['POST'])]
    public function cancel(Booking $booking, EntityManagerInterface $em): Response
    {
        if ($booking->getAppUser() !== $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas annuler une réservation car vous n\'êtes pas connecté(e).');
            return $this->redirectToRoute('login');
        }

        $booking->setBookingStatus(BookingStatus::CANCELLED);
        $em->flush();

        $this->addFlash('success', 'Réservation annulée avec succès.');

        return $this->redirectToRoute('bookings'); // ou une autre route selon ton app
    }

    #[Route('/calendar', name: 'app_booking_calendar')]
    public function calendar(): Response
    {
        return $this->render('booking/calendar.html.twig');
    }
}
