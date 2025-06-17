<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création d'un admin
        $admin = new User();
        $admin
            ->setUsername('admin')
            ->setEmail('admin@admin.fr')
            ->setPassword($this->hasher->hashPassword($admin, 'admin'))
            ->setPhoneNumber('0789092979')
            ->setSiret('02154786543598')
            ->setRoles(['ROLE_ADMIN'])
        ;
        $manager->persist($admin);
        $manager->flush();
        $this->addReference('user_admin', $admin); // Ajout de la référence utilisateur pour pouvoir ensuite l'utiliser dans BookingFixtures etc

        // Création de 10 users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user
                ->setUsername($faker->userName())
                ->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, 'user'))
                ->setPhoneNumber($faker->phoneNumber())
                ->setSiret($faker->numerify('##############'))
                ->setCompany($faker->company())
                ->setRoles(['ROLE_USER'])
            ;
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}
