<?php

namespace App\DataFixtures;

use App\Entity\Equipment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EquipmentFixtures extends Fixture
{
    public const EQUIPMENT_REFERENCE = 'equipment_';

    public function load(ObjectManager $manager): void
    {
        $equipmentNames = [
            'Projecteur',
            'Microphone',
            'Table de mixage',
            'Écran LED',
            'Ordinateur portable',
            'Caméra HD',
            'Hauts-parleurs',
            'Lumières d\'ambiance',
            'Pupitre',
            'Table de conférence',
        ];

        foreach ($equipmentNames as $index => $name) {
            $equipment = new Equipment();
            $equipment->setName($name);

            $manager->persist($equipment);

            // On sauvegarde une référence pour la réutiliser dans d'autres Fixtures (ex: EventRoom)
            $this->addReference(self::EQUIPMENT_REFERENCE . $index, $equipment);
        }

        $manager->flush();
    }
}
