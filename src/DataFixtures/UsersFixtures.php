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
    /** @var int $counter Counter for tracking iterations. */
    private $counter = 1;


    /**
     * UsersFixtures constructor.
     *
     * @param UserPasswordHasherInterface $passwordEncoder Password hasher for encoding user passwords.
     * @param SluggerInterface            $slugger         Slugger for generating slugs.
     */
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ) {

    }//end __construct()


    /**
     * Load dummy user data into the database.
     *
     * This function generates fake user data to simulate user registration.
     * User email, password, username, are randomized.
     *
     * @param ObjectManager $manager The entity manager to persist the data.
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($usr = 1 ; $usr <= 5 ; $usr++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, 'secret')
            );
            $user->setUserName($faker->userName);

            $manager->persist($user);

            $this->addReference('user_'.$this->counter, $user);
            $this->counter++;
        }

        $manager->flush();

    }//end load()

    
}
