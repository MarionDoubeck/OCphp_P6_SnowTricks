<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UsersFixtures extends Fixture
{
    private $counter = 1;

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ){}

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($usr = 1 ; $usr <= 5 ; $usr++){
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, 'secret')
            );
            $user->setUserName($faker->userName);
            $user->setAvatar($faker->imageUrl);

            $manager->persist($user);

            $this->addReference('user_'.$this->counter, $user);
            $this->counter++;

        }

        $manager->flush();
    }
}
