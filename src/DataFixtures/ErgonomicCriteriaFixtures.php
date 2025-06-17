<?php

namespace App\DataFixtures;

use App\Entity\ErgonomicCriteria;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ErgonomicCriteriaFixtures extends Fixture
{
    public const ERGONOMICCRITERIA_REFERENCE = 'ergonomic_criteria_';

    public function load(ObjectManager $manager): void
    {
        $ergonomicCriteriaNames = [
            'Accessibilité PMR',
            'Confort acoustique',
            'Luminosité naturelle',
            'Éclairage artificiel ajustable',
            'Température ambiante régulée',
            'Aération naturelle',
            'Isolation phonique',
            'Sécurité des installations',
            'Modularité de l\'espace',
            'Présence d\'un système de secours',
        ];

        foreach ($ergonomicCriteriaNames as $index => $name) {
            $ergonomicCriteria = new ErgonomicCriteria();
            $ergonomicCriteria->setName($name);

            $manager->persist($ergonomicCriteria);

            $this->addReference(self::ERGONOMICCRITERIA_REFERENCE . $index, $ergonomicCriteria);
        }

        $manager->flush();
    }
}
