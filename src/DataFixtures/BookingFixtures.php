<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Booking;
use App\Entity\EventRoom;
use App\Enum\BookingStatus;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $bookingStatusCases = BookingStatus::cases();

        for ($i = 0; $i < 15; $i++) {
            $booking = new Booking();

            /** @var User $user */
            $user = $this->getReference('user_' . $faker->numberBetween(0, 9), User::class);

            $eventRoomRef = 'event_room_' . $faker->numberBetween(0, 19);
            $eventRoom = $this->getReference($eventRoomRef, EventRoom::class);

            // Dates : date de début aléatoire dans 30 jours
            $dateStart = $faker->dateTimeBetween('now', '+30 days');
            // On remet les heures/minutes/secondes à 00:00:00 pour enlever les heures
            $dateStart->setTime(0, 0, 0);

            // Date de fin : entre 1 et 5 jours après dateStart
            $dateEnd = (clone $dateStart)->modify('+' . $faker->numberBetween(1, 5) . ' days');

            $status = $faker->randomElement($bookingStatusCases);

            $booking
                ->setAppUser($user)
                ->setEventRoom($eventRoom)
                ->setDateStart($dateStart)
                ->setDateEnd($dateEnd)
                ->setBookingStatus($status);

            $manager->persist($booking);
            $this->addReference('booking_' . $i, $booking);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            EventRoomFixtures::class, // supposé que tu as cette fixture
        ];
    }
}
