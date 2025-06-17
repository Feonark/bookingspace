<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\EventRoom;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EventRoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $eventRoom = new EventRoom();
            $eventRoom->setName('Salle ' . chr(65 + $i)) // Salle A, B, C, etc.
                ->setDescription($faker->paragraph(2))
                ->setCapacity($faker->numberBetween(10, 100));

            $manager->persist($eventRoom);

            // Ajout de la référence pour BookingFixtures
            $this->addReference('event_room_' . $i, $eventRoom);
        }

        $manager->flush();
    }
}
