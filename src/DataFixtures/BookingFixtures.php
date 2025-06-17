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

        // 15 bookings, mais on a 10 users, donc on boucle sur 10 users, on créera plusieurs bookings par user
        for ($i = 0; $i < 15; $i++) {
            $booking = new Booking();

            // On choisit un user au hasard parmi les 10 users créés
            /** @var User $user */
            $user = $this->getReference('user_' . $faker->numberBetween(0, 9), User::class);

            // Même principe pour eventRoom : on suppose qu'on a 5 event rooms référencées
            $eventRoomRef = 'event_room_' . $faker->numberBetween(0, 4);
            $eventRoom = $this->getReference($eventRoomRef, EventRoom::class);

            // Dates
            $dateStart = $faker->dateTimeBetween('now', '+30 days');
            $dateEnd = (clone $dateStart)->modify('+' . $faker->numberBetween(1, 3) . ' hours');

            $status = $faker->randomElement($bookingStatusCases);

            $booking
                ->setAppUser($user)
                ->setEventRoom($eventRoom)
                ->setDateStart($dateStart)
                ->setDateEnd($dateEnd)
                ->setBookingStatus($status)
            ;

            $manager->persist($booking);
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
