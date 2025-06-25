<?php

namespace App\Command;

use App\Entity\Booking;
use App\Entity\Notification;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:send-user-notifications',
    description: 'Envoie une notification aux utilisateurs dont la réservation commence dans 5 jours',
)]
class SendUserNotificationsCommand extends Command
{
    public function __construct(
        private readonly BookingRepository      $bookingRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $date = new \DateTimeImmutable(date("Y-m-d"));
        $date = $date->modify('+5 days');

        $bookings = $this->bookingRepository->getRemindBookingsByDate($date);
        $createdCount = 0;

        /** @var Booking $booking */
        foreach ($bookings as $booking) {
            // Crée et enregistre la nouvelle notification
            $notification = (new Notification())
                ->setUser($booking->getAppUser())
                ->setBooking($booking)
                ->setTitle(sprintf('La réservation %s commence le %s', $booking->getId(), $booking->getDateStart()->format('d/m/Y')))
                ->setMessage('La réservation commence sous 5 jours');

            $booking->setReminderNotificationCreated(true)
                ->setReminderNotificationSentAt(new \DateTime());

            $this->entityManager->persist($notification);
            $createdCount++;
        }

        $this->entityManager->flush();

        if ($createdCount > 0) {
            $io->success("Notifications créées : $createdCount");
        } else {
            $io->success('Aucune nouvelle notification à envoyer');
        }

        return Command::SUCCESS;
    }
}
