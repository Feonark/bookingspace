<?php

namespace App\DataFixtures;

use App\Entity\Software;
use App\Entity\ErgonomicCriteria;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SoftwareFixtures extends Fixture
{
    public const SOFTWARE_REFERENCE = 'software_';

    public function load(ObjectManager $manager): void
    {
        $softwareNames = [
            'grandMA3',
            'ChamSysMagicQ',
            'Avolites Titan',
            'Lightkey',
            'QLC+',
            'Luminair',
        ];

        foreach ($softwareNames as $index => $name) {
            $software = new Software();
            $software->setName($name);

            $manager->persist($software);

            $this->addReference(self::SOFTWARE_REFERENCE . $index, $software);
        }

        $manager->flush();
    }
}
