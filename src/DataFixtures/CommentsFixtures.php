<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommentsFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load dummy comment data into the database.
     *
     * This function generates fake comments to simulate user activity on the website.
     * Each comment contains random text with a length between 30 and 250 characters.
     * Comments are associated with users and tricks randomly.
     *
     * @param ObjectManager $manager The entity manager to persist the data.
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i=1; $i<=20; $i++){
            $comment = new Comment();
            $comment->setContent($faker->text(rand(30,250)));
            $user = $this->getReference('user_'.rand(1,5));
            $comment->setUser($user);
            $trick = $this->getReference('trick_'.rand(1,10));
            $comment->setTrick($trick);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($comment);
        }

        $manager->flush();
    }


    /**
     * Get the dependencies of this fixture.
     *
     * This function specifies the dependencies of this fixture on other fixtures.
     *
     * @return array An array of dependent fixture classes.
     */
    public function getDependencies():array
    {
        return [
            UsersFixtures::class,
            TricksFixtures::class
        ];
    }
}
