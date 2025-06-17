<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Booking;
use App\Enum\BookingStatus;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\EventRoomFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $booking = new Booking();

            /** @var \App\Entity\User $user */
            $user = $this->getReference('user_' . $i);
            /** @var \App\Entity\EventRoom $eventRoom */
            $eventRoom = $this->getReference('event_room_' . $faker->numberBetween(0, 4)); // Supposons que tu as 5 rooms

            $startDate = $faker->dateTimeBetween('now', '+1 week');
            $endDate = (clone $startDate)->modify('+' . mt_rand(1, 3) . ' days');

            $booking
                ->setAppUser($user)
                ->setEventRoom($eventRoom)
                ->setDateStart($startDate)
                ->setDateEnd($endDate)
                ->setBookingStatus(BookingStatus::PENDING); // Enum valeur

            $manager->persist($booking);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            EventRoomFixtures::class, // Important pour que les salles existent avant
        ];
    }
}
