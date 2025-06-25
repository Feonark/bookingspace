<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Software;
use App\Entity\Equipment;
use App\Entity\EventRoom;
use App\Entity\ErgonomicCriteria;
use App\DataFixtures\EquipmentFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventRoomFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Créons 5 salles d'événements
        for ($i = 0; $i < 20; $i++) {
            $eventRoom = new EventRoom();

            $image = 'eventroom' . $faker->numberBetween(1, 5) . '.jpg';

            $eventRoom
                ->setName($faker->words(3, true)) // 3 mots comme nom
                ->setDescription($faker->paragraph())
                ->setLocation($faker->city())
                ->setImage($image)
                ->setCapacity($faker->numberBetween(10, 100))
            ;

            // EQUIPEMENTS
            $numEquipments = $faker->numberBetween(2, 5);
            $equipmentIndices = $faker->randomElements(range(0, 9), $numEquipments);

            foreach ($equipmentIndices as $index) {
                $equipment = $this->getReference(EquipmentFixtures::EQUIPMENT_REFERENCE . $index, Equipment::class);
                $eventRoom->addEquipment($equipment);
            }

            // SOFTWARES
            $numSoftwares = $faker->numberBetween(2, 5);
            $softwareIndices = $faker->randomElements(range(0, 5), $numSoftwares);

            foreach ($softwareIndices as $index) {
                $software = $this->getReference(SoftwareFixtures::SOFTWARE_REFERENCE . $index, Software::class);
                $eventRoom->addSoftware($software);
            }

            // ERGONOMIC CRITERIAS
            $numErgonomicCriterias = $faker->numberBetween(2, 5);
            $ergonomicCriteriaIndices = $faker->randomElements(range(0, 9), $numErgonomicCriterias);

            foreach ($ergonomicCriteriaIndices as $index) {
                $ergonomicCriteria = $this->getReference(ErgonomicCriteriaFixtures::ERGONOMICCRITERIA_REFERENCE . $index, ErgonomicCriteria::class);
                $eventRoom->addErgonomicCriteria($ergonomicCriteria);
            }

            $manager->persist($eventRoom);

            $this->addReference('event_room_' . $i, $eventRoom);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EquipmentFixtures::class,
        ];
    }
}
