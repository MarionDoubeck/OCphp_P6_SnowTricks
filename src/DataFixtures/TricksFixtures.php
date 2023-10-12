<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var int $counter Counter for tracking iterations. */
    private $counter = 1;


    /**
     * Load dummy trick data into the database.
     *
     * This function generates fake trick data to simulate the addition of tricks.
     * Trick names, associated trick groups, users, and descriptions are randomized.
     *
     * @param ObjectManager $manager The entity manager to persist the data.
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $tricks = [
            ['Mute',1],
            ['Indy',1],
            ['Seat belt',1],
            ['180', 2],
            ['360', 2],
            ['720', 2],
            ['big foot', 2],
            ['Mac Twist', 3],
            ['Misty', 4],
            ['Method Air', 7]
        ];

        foreach ($tricks as $str) {
            $trick = new Trick();
            $trick->setName($str[0]);
            $group = $this->getReference('group_'.$str[1]);
            $trick->setTrickGroup($group);
            $user = $this->getReference('user_'.rand(1,5));
            $trick->setUser($user);
            $trick->setDescription($faker->text(200));
            $manager->persist($trick);

            $this->addReference('trick_'.$this->counter, $trick);
            $this->counter++;
        }

        $manager->flush();
    }//end load()


    /**
     * Get the dependencies for this fixture.
     *
     * This function returns an array of fixture classes that this fixture depends on.
     *
     * @return array
     */
    public function getDependencies():array
    {
        return [
            UsersFixtures::class
        ];
    }

}
