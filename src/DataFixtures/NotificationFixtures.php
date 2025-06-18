<?php

namespace App\DataFixtures;

use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NotificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Exemple : créer 10 notifications
        for ($i = 1; $i <= 10; $i++) {
            $notification = new Notification();
            $notification->setTitle("Notification $i");
            $notification->setMessage("Cet utilisateur demande à réserver une salle.");
            $notification->setCreatedAt(new \DateTimeImmutable());

            /** @var Booking $booking */
            $booking = $this->getReference("booking_" . rand(1, 5), Booking::class); // Suppose 5 bookings
            $notification->setBooking($booking);

            /** @var User $user */
            $user = $this->getReference("user_" . rand(1, 5), User::class); // Suppose 5 users
            $notification->setUser($user);

            $manager->persist($notification);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BookingFixtures::class,
        ];
    }
}
